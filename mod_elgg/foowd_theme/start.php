<?php

/**
* Theme for foowd platform
*
* @package foowd
*/


elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {

	//Rimuovo alcune cose che non servono
	elgg_unregister_menu_item('footer', 'powered');
	elgg_unextend_view('page/elements/header', 'search/search_box');
    
	//Registro le librerie javascript
	//Bootstrap
	elgg_register_js('bootstrap', '/mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js');
	//JQuery
	elgg_register_js('jquery','/mod/foowd_theme/vendor/jquery/dist/jquery.min.js');
	
	//Registro le librerie di style
	//Bootstrap
	elgg_register_css('bootstrap_css','/mod/foowd_theme/vendor/bootstrap/dist/css/bootstrap.min.css');
	
	//Registro i file javascript personalizzati
	elgg_register_js('toogle-layout','/mod/foowd_theme/lib/js/toogle-layout.js');
	elgg_register_js('ga','/mod/foowd_theme/lib/js/google-analytics.js');

	//Dico a Elgg di utilizzare i miei css personalizzati
	elgg_extend_view('css/elgg','foowd_theme/css');

	//Registro i page handler
	elgg_register_page_handler('activity', 'foowd_activity_page_handler');
}

function foowd_activity_page_handler() {
	elgg_load_css('bootstrap_css');
	if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
        return false;
    return true;
}





