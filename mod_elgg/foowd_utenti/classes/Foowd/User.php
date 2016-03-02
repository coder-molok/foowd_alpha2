<?php

namespace Foowd;

// Metadata associati all'utente:
// Genre , standard o offerente
// idAuth-{provider}, per coloro che si loggano tramite facebook/google+
// fake, valore lol solo per l'utente 373 (lo uso per test)
// Description , per gli offerenti
// Image, per gli offerenti

class User {


	public $form = null;

	public static $needForOfferente = array(/*'Description',*/'Site','Piva', 'Phone','Address','Company','Owner', 'City', 'Zipcode', /*'AddressesType', 'Civic'*/);

	public static $allUserFields = array('Name', 'Username', 'Email', 'Description', 'Genre' ,'Piva', 'Address','Company','Site','Phone', 'Owner', 'City', 'Zipcode'/*, 'AddressesType', 'Civic', 'Location'*/);

	/**
	 * registro un nuovo utente, aggiungendogli un metadato e salvandolo anche nel servizio API.
	 * Qualora il salvataggio API non si realizzasse, il nuovo utente non viene salvato.
	 *
	 * NB: questa funzione viene ad essere invocata alla registrazione da fronthend come hook soltanto dopo che l'action useradd.php ha dato esito positivo
	 * 
	 * @param  [type] $hook   [description]
	 * @param  [type] $type   [description]
	 * @param  [type] $value  [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function register($hook, $type, $value, $params){


		// Il form e' gia' sticky, e valore 'register'
		$form = $this->form;
		// \Uoowd\Logger::addInfo($form);
		
		// set sticky: avviso il sistema che gli input di questo form sono sticky
		elgg_make_sticky_form($form);

		// richiamo la classe che gestisce il form
		$f = new \Foowd\Action\Register();
		\Uoowd\Logger::addInfo('Tentativo di Registrazione');


		if(isset($_GET['email'])){
			$input = $_GET;
		}elseif(isset($_POST['email'])){
			$input = $_POST;
		}else{
			\Uoowd\Logger::addError('Il campo email non e\' impostato nel per $_GET ne per $_POST, pertanto non posso procedere.');
			return false;
		}
		
		// manageForm ritorna i dati estrapolati dai get_input: 
		//  se non si fa attenzione, potrebbero non essere coerenti con quelli dello sticky_form
		$genre = get_input('Genre');
		set_input('Genre', 'Genre-'.$genre);

		$genre = ($genre == 'offerente') ? 'evaluating' : $genre;

		// per eventuale registrazione mediante social
		$idAuth = get_input('idAuth');
		// set_input('idAuth', 'idAuth-'.$genre);

		$data = $f->manageForm($form);
		// \Uoowd\Logger::addError($form);
		// \Uoowd\Logger::addError($data);
	
		// NB: il check viene fatto sugli get_input, non sugli elgg_get_sticky
		if(!$f->status){
		    // nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
		    if(! $str = \Uoowd\Param::dbg()){ 
		        $str = "Errore nell'immissione dei dati";
		    }
		    \Uoowd\Logger::addError($str);
		    return false;
		}


		// recupero gli ultimi dati necessari, controllando ulteriormente la regolarita' del processo
		// l'entita' e' gia' definita, in quanto questo hook avviene dopo la registrazione
		$entity = $params['user'];
		$extId = $entity->guid;
		//To get its guid would
		$user = get_entity($extId);
		if (!$user){
		    if(! $str = \Uoowd\Param::dbg()){ 
		        $str = "elgg errore creazione utente lato Elgg";
		    }
		    \Uoowd\Logger::addError($str);
		    // reindirizzo
		    $this->wrap_forward_uservalidationbyemail('registration-error', elgg_get_site_url() . 'foowd_utenti/registration-error');
		    return false;
		}

		// salvo metadata: 
		$user->Genre = $genre;
		// \Uoowd\Logger::addError('registro auth: '.$idAuth);
		$user->idAuth = $idAuth;
		//i metadata vengono automaticamente salvati, pertanto questo comando posso evitarlo:
		//$user->save();

		// chiamata Api
		// \Uoowd\Logger::addError('chiamata API');
		$data['Genre'] = $genre;
		$data['Name'] = $user->name;
		$data['Username'] = $user->username;
		$data['type']= "create";
		$data['ExternalId'] = $extId;
		$data['Email'] = $user->email;
		// se e' un offerente, lo metto in stato di valutazione ....
		if($data['Genre']=='evaluating'){
			$need = self::$needForOfferente;

			foreach ($need as $field) {			
				// se non e' vuoto e se esiste
				if(get_input($field)!=='' && get_input($field) ){
					$data[$field]=get_input($field);
				}else{
					// il sito e' opzionale
					if($field === 'Site') continue;
					$EmptyNeed[$field]=$field;
				}
			}
			
		}
		

		if(isset($EmptyNeed)){
			\Uoowd\Logger::addError("Mancano campi obbligatori");
			\Uoowd\Logger::addError($EmptyNeed);
			// eventualmente aggiungere allo sticky form per visualizzare gli errori
			register_error('Siamo spiacenti ma mancano dei campi:<br/> ' . implode(', ', $EmptyNeed));

			// elimino l'utente che avevo appena creato: devo avere l'accesso per questo, in quanto l'utente attuale non risulta loggato!
			$access = elgg_set_ignore_access(true);
			$user->delete(true);
			$user->save();
			elgg_set_ignore_access($access);
			// reindirizzo
			$this->wrap_forward_uservalidationbyemail('registration-error', REFERER);
			return false;
		}
		
		// \Uoowd\Logger::addError($data);
		// if(get_input('file')!=='') $data['Image']=get_input('file');
		// \Uoowd\Logger::addError("prima di offerente: il genere e' ".$genre);
		/* attualmente la parte delle immagini viene saltata
		if($genre === 'offerente'){
			// \Uoowd\Logger::addError("dentro a offerente");
			$crop = new \Uoowd\FoowdCrop();
			$dir = 'User-'.$extId.'/profile/';
			// \Uoowd\Logger::addError($_FILES);
			// prima di inviare, tolgo i campi Files con nome vuoto, perche' equivalgono a input non riempiti
			foreach ($_FILES as $key => $value) {
				if($value['name']=='') unset($_FILES[$key]);
			}
			\Uoowd\Logger::addError($_FILES);
			$crop->saveImgEach($dir, $extId, $form, $input);
	
			if(!$crop->cropCheck()){
				\Uoowd\Logger::addError("qualcosa e' andato storto nel crop");
				$crop->removeDir(\Uoowd\Param::imgStore().'User-'.$extId);
				return false;
			}else{
				// se tutto e' andato a buon fine, modifico i nomi dei file togliendo la parola "file"
				$path = \Uoowd\Param::pathStore($extId,'profile');
				foreach( new \DirectoryIterator($path) as $fileInfo){
					// dentro directory file#
					if($fileInfo->isDir() && !$fileInfo->isDot() ){
						foreach(new \DirectoryIterator($fileInfo->getPathname()) as $file){
							if($file->isFile()){
								$dir = $file->getPath() . DIRECTORY_SEPARATOR;
								$newName = str_replace('file', '', $file->getFilename());
								rename($file->getPathname(), $dir.$newName);
							}
							// dentro file#/small, big o medium
							if($file->isDir() && !$file->isDot() ){
								foreach(new \DirectoryIterator($file->getPathname()) as $f){
									if($f->isFile()){
										$dir = $f->getPath() . DIRECTORY_SEPARATOR;
										$newName = str_replace('file', '', $f->getFilename());
										rename($f->getPathname(), $dir.$newName);
									}
								}	
							}
						}	
					}
				}
			}
			// se volessi salvare l'immagine
			// $data['Image'] = $crop->base64();
		}*/

		// \Uoowd\Logger::addError("dopo offerente");
		// recupero i dati che dovrei aver salvato nel db e verifico
		$r = \Uoowd\API::Request('user', 'POST', $data);
		// \Uoowd\Logger::addError($r);

		if(!is_object($r)){
		    if(! $str = \Uoowd\Param::dbg()){ 
		        $str = "Errore Curl.";
		    }
		    \Uoowd\Logger::addError($str);
		    // elimino l'utente che avevo appena creato: devo avere l'accesso per questo, in quanto l'utente attuale non risulta loggato!
		    $access = elgg_set_ignore_access(true);
		    $user->delete(true);
		    $user->save();
		    elgg_set_ignore_access($access);
		    // reindirizzo
		    $this->wrap_forward_uservalidationbyemail('registration-error', elgg_get_site_url() . 'foowd_utenti/registration-error');
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
			// \Uoowd\Logger::addError("dentro else");
		    
			if(isset($crop)) $crop->removeDir(\Uoowd\Param::imgStore().'User-'.$extId);

		    // aggiungo gli errori ritornati dalle API esterne
		    $errors = array_keys(get_object_vars($r->errors));
		    $f->addError(array_values($errors), $form);

		    // nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
		    if(! $str = \Uoowd\Param::dbg()){ 
		        $str = "Errore remoto. Dati: ritornati";
		    }
		    \Uoowd\Logger::addError($str);
		    \Uoowd\Logger::addError($r);

		    // elimino l'utente che avevo appena creato: devo avere l'accesso per questo, in quanto l'utente attuale non risulta loggato!
		    $access = elgg_set_ignore_access(true);
		    $user->delete(true);
		    $user->save();
		    elgg_set_ignore_access($access);
		    // reindirizzo
		    $this->wrap_forward_uservalidationbyemail('registration-error', elgg_get_site_url() . 'foowd_utenti/registration-error');
		    return false;
		}
		
		return true;

	}


	/**
	 * sovrascrivo il forward di uservalidationbyemail
	 *
	 * NB: controlla di aver eliminato l'utente, visto che probabilmente hai incontrato un errore nella registrazione
	 * 
	 * @param  [type] $foowdForward [description]
	 * @param  [type] $forwardUrl   [description]
	 * @return [type]               [description]
	 */
	public function wrap_forward_uservalidationbyemail($foowdForward, $forwardUrl){
		$session = elgg_get_session();
		$session->set('foowdForward', $foowdForward);
		// vedere in start.php l'hook alla funzione forward
		forward($forwardUrl);
	}




}
