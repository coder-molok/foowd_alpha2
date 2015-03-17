<?php
gatekeeper();

$form = 'foowd_offerte/update';

// set sticky: avviso il sistema che gli input di questo form sono sticky
elgg_make_sticky_form($form);

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();

//da RIVEDERE: in fondo il modello e' definito tutto nella classe FormAdd
// sarebbe meglio implementare tutto da lui, magari mediante una classe astratta con parametri fissi che vengono estesi!
foreach(elgg_get_sticky_values($form) as $field => $value){
	if(! preg_match('@_{2,}@', $field)) $data[$field] = $value;
}

$data['publisher']=elgg_get_logged_in_user_guid();
$data['price'] = get_input('price');


// controllo eventuali errori
$import = $f->checkError('price', $data['price'], $form);



// attualmente testo solo il formato del prezzo
$success = ( $import );
//**************


if(!$success) forward(REFERER);

// se tutto va a buon fine, proseguo con le API esterne

$api = new \Foowd\API();
if($api){
	$api->Update('offer', $data);
	$r = $api->stop();
	
	// se sono qui la validazione lato elgg e' andata bene
	// ma ora controllo quella lato API remote
	if($r->response){
		// dico al sistema di scartare gli input di questo form
		// elgg_clear_sticky_form('foowd_offerte/add');
		$input = (array) $r->body[0];
		//$_SESSION['my'] = $input;

		// sempre cancellare dopo un successo,
		// in modo da forzare, secondo il mio algoritmo, il ricaricamento dei dati
		elgg_clear_sticky_form($form);

		system_message(elgg_echo("aggiornato post ".$data['id']));
		forward('foowd_offerte/success');

	}else{

		$_SESSION['sticky_forms'][$form]['apiError']=$r;
		register_error(elgg_echo('Non riesco a caricare l\'offerta'));
	}
}
