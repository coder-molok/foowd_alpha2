<?php

/**
* Theme for foowd platform
*
* @package foowd
*/


elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {

	//Registro i page handler
	//elgg_register_page_handler('activity', 'foowd_wall_page_handler');
	elgg_register_plugin_hook_handler('index', 'system', 'foowd_wall_page_handler');
	//pagina di profilo dell'utente
	//elgg_register_page_handler('profile','foowd_user_profile_page_handler');

	elgg_register_page_handler('detail', 'foowd_product_detail_page_handler');

	// parte aggiunta da Simone Scardoni
	AMD();

}

function foowd_wall_page_handler() {
	// if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
	if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
		return false;
	return true;
}

function foowd_user_profile_page_handler() {
	if(elgg_get_logged_in_user_guid() != 0){
		if (!include_once(dirname(__FILE__) . "/pages/user-profile.php"))
			return false;
		return true;
	}
	forward("login");
}

function foowd_product_detail_page_handler(){
	if (!include_once(dirname(__FILE__) . "/pages/product-detail.php"))
		return false;
	return true;
}


// AMD LOAD
function AMD(){
	/* 
	 * Librerie
	 */
	elgg_define_js('jquery', [
	    	'src' => 'mod/foowd_theme/vendor/jquery/dist/jquery.min.js'
	]);
	
	elgg_define_js('handlebars', [
    	'src' => '/mod/foowd_theme/vendor/handlebars/handlebars.amd.min.js',
    	// 'deps' => array('templates'),
	   	// 'exports' => 'Handlebars'
	]);

	elgg_define_js('handlebars.runtime', [
    	'src' => '/mod/foowd_theme/vendor/handlebars/handlebars.runtime.amd.min.js',
    	// 'deps' => array('templates'),
	   	// 'exports' => 'Handlebars'
	]);

	elgg_define_js('bootstrap', [
	    	'src' => 'mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js',
	    	'deps' => array('jquery')
	]);
	/* 
	 * Template di Handlebars Precompilati
	 */
	elgg_define_js('templates', [
	    	'src' => '/mod/foowd_theme/pages/templates/templates-amd.js',
	    	// 'deps'=> array('handlebars')
	]);
	/* 
	 * Moduli custom foowd
	 */
	elgg_define_js('foowdAPI',[
	    'src' => '/mod/foowd_theme/lib/js/foowd/foowdAPI-AMD.js',
	    'deps'=> array('jquery', 'elgg')
	]);	

	elgg_define_js('WallController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/WallController.js',
	    'deps'=>array('templates', 'elgg', 'handlebars', 'page' , 'jquery' )
	]);

	elgg_define_js('ProductDetailController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/ProductDetailController.js',
	    'deps'=>array('templates', 'elgg', 'handlebars', 'page' , 'jquery' )
	]);

}
