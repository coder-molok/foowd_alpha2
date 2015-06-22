<?php

gatekeeper();

$form = \Uoowd\Param::pid().'/add';



// set sticky: avviso il sistema che gli inpu di questo form sono sticky
elgg_make_sticky_form($form);

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();


// Preparo il Tag passato mediante checkbox
$tag = array();
foreach(get_input('Tag') as $val) if($val!='0') array_push($tag, $val);

// NB: set input imposta il valore all'input del form, ma non cambia il corrispettivo in $_SESSION!!
// pertanto se non faccio altro, il valore di ritorno di TAG rimane l'array che avevo passato
// e non la stringa che imposto di seguito
set_input('Tag', implode( ' , ', $tag)  );
$_SESSION['sticky_forms'][$form]['str']=$_SESSION['sticky_forms'][$form]['Tag'];

$data = $f->manageForm($form);

// imposto la data
// $data['Created']=date('Y-m-d H:i:s');
$data['Publisher']=elgg_get_logged_in_user_guid();

// \Uoowd\Logger::addNotice(elgg_get_sticky_values($form));
// \Uoowd\Logger::addNotice($data);

// ora parto a controllare il file
$crop = new \Uoowd\Crop();

// $_SESSION['sticky_forms'][$form] = $data;

if ($f->status && $crop->status ) {

	// message_system('partenza');


	//$_SESSION['my']=$data;
	$data['type']='create';
	$r = \Uoowd\API::Request('offer', 'POST', $data);

	// se sono qui la validazione lato elgg e' andata bene
	// ma ora controllo quella lato API remote
	if($r->response){

		// register_error('resp non torna true');

		// system_message('resp true');

		// dopo aver salvato i contenuti del post posso provare a salvare le immagini
		set_input('offerGuid', $r->Id);
		$crop->saveImg();

		// se il crop non e' avvenuto, allora elimino l'articolo salvato con le API
		// recupero l'id dell'offerta: mi serve per creare la directory in cui salvare
		if(! $crop->status){
			
			// elimino la directory delle immagini precedentemente creata
			$crop->removeDir();

			$data2['Publisher']=elgg_get_logged_in_user_guid();
			$data2['Id']=(int) $r->Id;
			$data2['type']='delete';

			$r2 = \Uoowd\API::Request('offer','POST', $data2);
			if($r2->response){
				// system_message(elgg_echo("eliminato il post ".$data2['Id']));
			}else{

				// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
				if(! $str = \Uoowd\Param::dbg()){ 
					$str = "errore nell'eliminazione di offerta numero ".$data2['Id'];
				}
				register_error($str);
			}

			forward(REFERER);
		}
		
		// se tutto e' andato a buon fine, posso eliminare lo sticky
		elgg_clear_sticky_form($form);
		
		// rimando alla pagina di successo
		forward(\Uoowd\Param::pid().'/success');	

	}else{
		
		// aggiungo gli errori ritornati dalle API esterne
		$errors = array_keys(get_object_vars($r->errors));

		// aggiungo eventuali errori allo $_SESSION['sticky_forms'][$form]
		$f->addError(array_values($errors), $form);

		// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
		if(! $str = \Uoowd\Param::dbg()){ 
			$str = "Uno o piu campi sono errati";
		}
		register_error($str);
		// register_error('lol');
		//$_SESSION['sticky_forms']['foowd_offerte/add']['apiError']=$r;
	}

} else {

	// register_error('qualcosa non torna');
	  
}
