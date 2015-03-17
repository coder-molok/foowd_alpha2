<?php

gatekeeper();


// set sticky: avviso il sistema che gli inpu di questo form sono sticky
elgg_make_sticky_form('foowd_offerte/add');

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();

//da RIVEDERE: in fondo il modello e' definito tutto nella classe FormAdd
// sarebbe meglio implementare tutto da lui, magari mediante una classe astratta con parametri fissi che vengono estesi!
$data['tags'] = get_input('tags');
$data['description'] = get_input('description');
$data['publisher']=elgg_get_logged_in_user_guid();

// eseguo i check dei vari input
$data['name'] = get_input('name');
$f->checkError('name', $data['name'], 'foowd_offerte/add');

$data['price'] = get_input('price');
$import = $f->checkError('price', $data['price'], 'foowd_offerte/add');

//$tags = string_to_tag_array(get_input('tags'));

// attualmente testo solo il formato del prezzo
$success = ( $import );

if ($success) {
	
	$api = new \Foowd\API();
	if($api){
		$_SESSION['my']=$data;
		$api->Create('offer', $data);
		$r = $api->stop();
		
		// se sono qui la validazione lato elgg e' andata bene
		// ma ora controllo quella lato API remote
		if($r->response){
			// dico al sistema di scartare gli input di questo form
			elgg_clear_sticky_form('foowd_offerte/add');

			system_message(elgg_echo('success'));
			// rimando alla pagina di successo
			forward('foowd_offerte/success');			
		}else{

			// aggiungo gli errori ritornati dalle API esterne
			$errors = array_keys(get_object_vars($r->errors));
			$f->addError(array_values($errors), 'foowd_offerte/add');

			register_error(elgg_echo("Uno o piu campi sono errati"));

			//$_SESSION['sticky_forms']['foowd_offerte/add']['apiError']=$r;
		}
	}

} else {

	// scrivo un errore, e in automatico ritorna alla pagina del form
  
}

