<?php

gatekeeper();

$form = \Uoowd\Param::pid().'/update';

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();

// la prima volta che chiamo la pagina il form non e' sticky, 
// pertanto lo rendo tale e inizializzo i parametri per il form
if(!elgg_is_sticky_form($form) ){
	elgg_make_sticky_form($form);

	// sarebbe meglio implementare tutto da lui, magari mediante una classe astratta con parametri fissi che vengono estesi!
	$data['Publisher']=elgg_get_logged_in_user_guid();
	$data['Id'] = get_input('Id');
	$data['type']='search';
	
	// trasformo l'array associativo in una stringa da passare come URI
	$url=preg_replace('/^(.*)$/e', '"$1=". $data["$1"].""',array_flip($data));
	$url=implode('&' , $url);
	
	// prendo i valori del vecchio post e li carico nel form
	$r = \Uoowd\API::Request('offer?'.$url,'GET');

	// se sono qui la validazione lato elgg e' andata bene
	// ma ora controllo quella lato API remote

	
	if($r->response){
		// dico al sistema di scartare gli input di questo form
		// elgg_clear_sticky_form('foowd_offerte/add');
		$input = (array) $r->body[0];
		$input['Id'] = get_input('Id');
		// salvo nello sticky form tutti i dati ritornati dalla API
		$f->manageSticky($input, $form);
	}else{
		$_SESSION['sticky_forms'][$form]['apiError']=$r;
		register_error(elgg_echo('Non riesco a caricare l\'offerta'));
	}
}


$title = "Modifica la tua Offerta";
$content = elgg_view_title($title);


$vars = $f->prepare_form_vars($form);

// add the form stored in /views/default/forms/foowd_offerte/add.php
$content .= elgg_view_form($form, array($form), $vars);

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page one_sidebar
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content
));

// draw the page
echo elgg_view_page($title, $body);


