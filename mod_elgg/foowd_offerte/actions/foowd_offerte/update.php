<?php
gatekeeper();

$form = \Foowd\Param::pid().'/update';

// set sticky: avviso il sistema che gli input di questo form sono sticky
elgg_make_sticky_form($form);

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();

// loop necessario per impostare i dati get_input su cui si basa il successivo manageForm
foreach(elgg_get_sticky_values($form) as $field => $value){
	if(! preg_match('@_{2,}@', $field)) set_input($field,$value);
}

$data = $f->manageForm($form);

// imposto la data
$data['Modified'] =date('Y-m-d H:i:s');
$data['Publisher']=elgg_get_logged_in_user_guid();

if(!$f->status) forward(REFERER);

// se tutto va a buon fine, proseguo con le API esterne
$data['type']='update';
$r = \Foowd\API::Request('offers', 'POST', $data);

if($r->response){
	// dico al sistema di scartare gli input di questo form
	// elgg_clear_sticky_form('foowd_offerte/add');
	$input = (array) $r->body[0];

	// sempre cancellare dopo un successo,
	// in modo da forzare, secondo il mio algoritmo, il ricaricamento dei dati
	elgg_clear_sticky_form($form);
	system_message(elgg_echo("aggiornato post ".$data['Id']));
	forward('foowd_offerte/success');
}else{
	$_SESSION['sticky_forms'][$form]['apiError']=$r;
	register_error(elgg_echo('Non riesco a caricare l\'offerta'));
}

