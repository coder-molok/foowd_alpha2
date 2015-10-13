<?php

gatekeeper();

$form = \Uoowd\Param::pid().'/update';

// metodo per istanziare la variabile $session se lo sticky esiste
// in particolare mi serve per l'array_merge prima di richiamare la view
($session = $_SESSION['sticky_forms'][$form]); 

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

		// quando arriva dalle API e' una stringa da trasformare in array.
		// invece dopo il submit del form, e' un array
		$input['Tag'] = array_map('trim', explode(',', $input['Tag'] ));

		// salvo nello sticky form tutti i dati ritornati dalla API
		$f->manageSticky($input, $form);
	}else{
		$_SESSION['sticky_forms'][$form]['apiError']=$r;
		register_error(elgg_echo('Non riesco a caricare l\'offerta'));
	}
}


$title = "Modifica la tua Offerta";
$content = elgg_view_title($title);

// \Fprint::r(elgg_get_sticky_values($form));
// \Fprint::r($_SESSION['sticky_forms']);
$vars = $f->prepare_form_vars($form);


///// preparo i valori da passare alla view
// $fadd->createField('Tag', 'Tags (singole parole separate da una virgola) *', 'input/text');

// mi serve perche' lo uso come default
$value = elgg_get_plugin_setting('tags', \Uoowd\Param::uid());
$value = json_decode($value);
// \Fprint::r($vars['Tag']);
$checkBox = array();
foreach($value as $category => $obj){
	// var_dump($category);
	$i = 0;
	foreach($obj as $single){
		if(in_array( $single, $vars['Tag'] )){
		    $checked = true;
		}else{
		    $checked = false;
		}	
		// $var_dump($single);
		$checkBox[$category][$i++] = array('tag'=>$single, 'checked'=>$checked);
	}
}

// altrimenti verrebbe riscritto nell'array_merge qui sotto
unset($session['Tag']);
$vars['Tag'] = $checkBox;
// per il css del box contenitore
$vars['TagAttributes'] = array('class' => 'foowd-Tag');

// recupero le unita'
$unit = \Uoowd\Param::unit();
// $u['name']="Unit";
if(!isset($vars['Unit'])) $u['options_values']=array(''=>'-- scegli un valore --');
foreach($unit as $obj){
	foreach ($obj as $unit => $symbol) {
		$u['options_values'][$unit]=sprintf('%s (%s)<br/>', ucwords(str_replace('_',' ',$unit)), $symbol);
		// $u['options'][]=$unit;
	}
}
// NB: l'underscore server per non metchare il vero field, che contiene lo sticky value
$vars['_Unit'] = $u;


// aggiungo il nome del form alle variabili, visto che usero' sticky e $fadd della view dovra' chiamare \Uoowd\Sticky
// altri valori utili per il form
$vars['guid']=elgg_get_logged_in_user_guid();
$vars['sticky']=$form;

$vars = array_merge($vars, (array) $session);
// \Fprint::r($vars);
$content .= elgg_view_form($form, array('enctype'=>'multipart/form-data'), $vars);

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page one_sidebar
// $body = elgg_view_layout('one_sidebar', array(
//    'content' => $content
// ));
$body = $content;

// draw the page
echo elgg_view_page($title, '<div class="foowd-page-single">'.$body.'</div>');


unset($_SESSION['sticky_forms'][$form]);
