<?php

// set sticky: avviso il sistema che gli inpu di questo form sono sticky
elgg_make_sticky_form('foowd_offerte/add');

// richiamo la classe che gestisce il form
$f = new \Foowd\FormAdd();

// eseguo i check dei vari input
$title = get_input('title');
$f->checkError($title);

$import = get_input('import');
$import = $f->checkError('import',$import);

//$tags = string_to_tag_array(get_input('tags'));

// attualmente testo solo il formato del prezzo
$success = (true && $import );

if ($success) {
	// dico al sistema di scartare gli input di questo form
	elgg_clear_sticky_form('foowd_offerte/add');
	
  	//system_message(elgg_echo('success'));
  	// rimando alla pagina di successo
  	forward('foowd_offerte/success');
} else {
	// scrivo un errore, e in automatico ritorna alla pagina del form
  	register_error(elgg_echo('Error'));
}
