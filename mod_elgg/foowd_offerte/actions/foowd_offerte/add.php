<?php

gatekeeper();

$form = \Uoowd\Param::pid().'/add';

// set sticky: avviso il sistema che gli inpu di questo form sono sticky
elgg_make_sticky_form($form);

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();

$data = $f->manageForm($form);

// imposto la data
$data['Created']=date('Y-m-d H:i:s');
$data['Publisher']=elgg_get_logged_in_user_guid();

// \Uoowd\Logger::addNotice(elgg_get_sticky_values($form));
// \Uoowd\Logger::addNotice($data);

if ($f->status) {
	
	//$_SESSION['my']=$data;
	$data['type']='create';
	$r = \Uoowd\API::Request('offer', 'POST', $data);
			// se sono qui la validazione lato elgg e' andata bene
	// ma ora controllo quella lato API remote
	if($r->response){

		
		// dico al sistema di scartare gli input di questo form
		elgg_clear_sticky_form($form);
		system_message(elgg_echo('success'));
		
		// rimando alla pagina di successo
		forward(\Uoowd\Param::pid().'/success');	

	}else{
		
		// aggiungo gli errori ritornati dalle API esterne
		$errors = array_keys(get_object_vars($r->errors));
		$f->addError(array_values($errors), $form);

		// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
		if(! $str = \Uoowd\Param::dbg()){ 
			$str = "Uno o piu campi sono errati";
		}
		register_error($str);
		//$_SESSION['sticky_forms']['foowd_offerte/add']['apiError']=$r;
	}

} else {

	// scrivo un errore, e in automatico ritorna alla pagina del form
  
}
