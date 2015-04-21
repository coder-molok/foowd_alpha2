<?php

namespace Foowd;

class User {

	/**
	 * registro un nuovo utente, aggiungendogli un metadato e salvandolo anche nel servizio API.
	 * Qualora il salvataggio API non si realizzasse, il nuovo utente non viene salvato.
	 * 
	 * @param  [type] $hook   [description]
	 * @param  [type] $type   [description]
	 * @param  [type] $value  [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function register($hook, $type, $value, $params){


		// Il form e' gia' sticky, e valore 'register'
		$form = 'register';

		// richiamo la classe che gestisce il form
		$f = new \Foowd\Action\Register();

		// manageForm ritorna i dati estrapolati dai get_input: 
		//  se non si fa attenzione, potrebbero non essere coerenti con quelli dello sticky_form
		$genre = get_input('Genre');
		set_input('Genre', 'Genre-'.$genre);
		$data = $f->manageForm($form);
		
		// NB: il check viene fatto sugli get_input, non sugli elgg_get_sticky
		if(!$f->status){
		    // nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
		    if(! $str = \Foowd\Param::dbg()){ 
		        $str = "Errore nell'immissione dei dati";
		    }
		    register_error($str);
		    return false;
		}


		// recupero gli ultimi dati necessari, controllando ulteriormente la regolarita' del processo
		// l'entita' e' gia' definita, in quanto questo hook avviene dopo la registrazione
		$entity = $params['user'];
		$extId = $entity->guid;
		//To get its guid would
		$user = get_entity($extId);
		if (!$user){
		    if(! $str = \Foowd\Param::dbg()){ 
		        $str = "elgg errore creazione utente";
		    }
		    register_error($str);
		    return false;
		}

		// salvo metadata: 
		$user->Genre = $genre;
		//i metadata vengono automaticamente salvati, pertanto questo comando posso evitarlo:
		//$user->save();


		// chiamata Api
		$data['Genre'] = $genre;
		$data['Name'] = get_input('name');
		$data['type']= "create";
		$data['ExternalId'] = $extId;

		$r = \Uoowd\API::Request('user', 'POST', $data);

		if(!is_object($r)){
		    if(! $str = \Uoowd\Param::dbg()){ 
		        $str = "Errore Curl.";
		    }
		    register_error($str);
		    return false;
		}
		
		// se anche in remoto e' andata bene
		if($r->response){
		    
		    // dico al sistema di scartare gli input di questo form
		    elgg_clear_sticky_form($form);
		    system_message(elgg_echo('success'));
		    
		    // rimando alla pagina di successo
		    //forward(\Foowd\Param::pid().'/success');    
		    //\mod\uservalidationbyemail\pages\emailsent
		    //uservalidationbyemail/emailsent

		}else{
		    
		    // aggiungo gli errori ritornati dalle API esterne
		    $errors = array_keys(get_object_vars($r->errors));
		    $f->addError(array_values($errors), $form);

		    // nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
		    if(! $str = \Uoowd\Param::dbg()){ 
		        $str = "Errore remoto.";
		    }
		    register_error($str);
		    return false;
		}
		
		return true;

	}




}
