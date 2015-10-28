<?php

elgg_gatekeeper();

ob_start();
// var_dump($_SESSION['sticky_forms']['foowd-dati']);
unset($_SESSION['sticky_forms']['foowd-dati']);

$guid = elgg_get_logged_in_user_guid();

$user = elgg_get_logged_in_user_entity();

$form = 'foowd-dati';
$f = new \Foowd\Action\UserSave();

// prendo i valori del vecchio post e li carico nel form
$data['type']='search';
$data['ExternalId'] = $guid;

$r = \Uoowd\API::Request('user','POST', $data);

// se sono qui la validazione lato elgg e' andata bene
// ma ora controllo quella lato API remote


if($r->response){
	// var_dump($r);
	// dico al sistema di scartare gli input di questo form
	// elgg_clear_sticky_form('foowd_offerte/add');
	$input = (array) $r->body;

	// salvo nello sticky form tutti i dati ritornati dalla API
	$f->manageSticky($input, $form);
}else{
	$_SESSION['sticky_forms'][$form]['apiError']=$r;
	register_error(elgg_echo('Non riesco a caricare i dati'));
}

$vars = $f->prepare_form_vars($form);
$vars['guid']=$guid;
$vars['Email'] = $user->email;

// var_dump($user);
// var_dump($vars);


echo elgg_view_form('foowd-dati', array(), $vars);

$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-dati">'.$body.'</div>';

echo elgg_view_page('Settings',$body);

