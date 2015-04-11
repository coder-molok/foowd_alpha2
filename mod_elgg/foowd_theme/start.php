<?php

/**
* Theme for foowd platform
*
* @package foowd
*/


elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {
    
	//Carico le librerie javascript
	//Handlebars
	elgg_register_js('handlebars', '/mod/foowd_theme/vendor/handlebars/handlebars.min.js');
	elgg_load_js('handlebars');
	//Bootstrap
	elgg_register_js('bootstrap', '/mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js');
	elgg_load_js('bootstrap');
	//JQuery
	elgg_register_js('jquery','/mod/foowd_theme/vendor/jquery/dist/jquery.min.js');
	elgg_load_js('jquery');

	//carico librerie di style
	//Bootstrap
	elgg_register_css('bootstrap_css','/mod/foowd_theme/vendor/bootstrap/dist/css/bootstrap.min.css');
	elgg_load_css('bootstrap_css');

	//Carico i file javascript personalizzati
	//Templating del prodotto
	elgg_register_js('foowd_product','mod/foowd_theme/lib/js/product.js','footer');
	elgg_load_js('foowd_product');

	elgg_register_page_handler('', 'new_index');
}

function new_index() {
    if (!include_once(dirname(__FILE__) . "/index.php"))
        return false;

    return true;
}
