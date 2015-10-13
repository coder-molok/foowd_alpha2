<?php

/**
* Theme for foowd platform
*
* @package foowd
*/


elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {

	
	elgg_register_page_handler('','foowd_wall_page_handler');
	elgg_register_page_handler('detail', 'foowd_product_detail_page_handler');
	elgg_register_page_handler('board', 'foowd_user_preference_page_handler');
	elgg_register_page_handler('producer', 'foowd_producer_page_handler');
	elgg_register_page_handler('panel', 'foowd_panel_page_handler');

	// Registrazione dei css e js: attenzione ai nomi
	// NB: i files vengono solamente registrati, mentre il caricamento deve essere esplicitato
	foowd_theme_register();

	// caricamento dei moduli Javascript
	AMD();

	// raccolta di comandi per modificare la presentazione standard del tema lato elgg
	foowd_theme_elgg_manage();

}

function foowd_wall_page_handler() {
	if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
		return false;
	return true;
}

function foowd_product_detail_page_handler(){
	if (!include_once(dirname(__FILE__) . "/pages/product-detail.php"))
		return false;
	return true;
}

function foowd_producer_page_handler(){
	if (!include_once(dirname(__FILE__) . "/pages/producer.php"))
		return false;
	return true;
}

function foowd_user_preference_page_handler(){
	if(elgg_get_logged_in_user_entity() != 0){
		if (!include_once(dirname(__FILE__) . "/pages/user-preferences.php"))
			return false;
		return true;
	}
	forward("login");
	return true;
}
function foowd_panel_page_handler(){
	if(elgg_get_logged_in_user_entity() != 0){
		if (!include_once(dirname(__FILE__) . "/pages/panel.php"))
			return false;
		return true;
	}
	forward("login");
	return true;
}

// AMD LOAD
function AMD(){
	/* 
	 * Librerie
	 */
	elgg_define_js('jquery', [
		'src' => 'mod/foowd_theme/vendor/jquery/dist/jquery.min.js',
	]);
	
	elgg_define_js('handlebars', [
    	'src' => '/mod/foowd_theme/vendor/handlebars/handlebars.amd.min.js',
	]);

	elgg_define_js('handlebars.runtime', [
    	'src' => '/mod/foowd_theme/vendor/handlebars/handlebars.runtime.amd.min.js',
	]);

	elgg_define_js('bootstrap', [
    	'src' => 'mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js',
    	'deps' => array('jquery')
	]);
	elgg_define_js('masonry', [
    	'src' => 'mod/foowd_theme/vendor/masonry/dist/masonry.pkgd.min.js',
	]);

	/* 
	 * Grid loading
	 */
	elgg_define_js('imagesLoaded', [
    	'src' => 'mod/foowd_theme/vendor/imagesloaded/imagesloaded.pkgd.min.js',
	]);
	elgg_define_js('classie', [
    	'src' => 'mod/foowd_theme/assets/grid-loading/js/classie.js',
	]);
	elgg_define_js('animOnScroll', [
    	'src' => 'mod/foowd_theme/assets/grid-loading/js/AnimOnScroll.js',
	]);

	/* 
	 * Template di Handlebars Precompilati
	 */
	elgg_define_js('helpers', [
		'src' => '/mod/foowd_theme/lib/js/HandlebarsHelpers.js',
    	'deps'=> array('handlebars.runtime')
	]);

	/* 
	 * Helpers di handlebars
	 */
	elgg_define_js('templates', [
    	'src' => '/mod/foowd_theme/pages/templates/templates-amd.js',
    	'deps'=> array('handlebars', 'handlebars.runtime','helpers')
	]);

	/* 
	 * Moduli custom foowd
	 */
	elgg_define_js('FoowdAPI',[
	    'src' => '/mod/foowd_theme/lib/js/foowd/FoowdAPI.js',
	    'deps'=> array('jquery', 'elgg', 'utility-settings')
	]);	

	elgg_define_js('WallController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/WallController.js',
	    'deps'=> array('FoowdAPI','templates', 'elgg', 'page' , 'jquery')
	]);

	elgg_define_js('ProductDetailController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/ProductDetailController.js',
	    'deps'=> array('FoowdAPI','templates', 'elgg', 'handlebars', 'page' , 'jquery' , 'bootstrap')
	]);

	elgg_define_js('NavbarController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/NavbarController.js'
	]);
	
	elgg_define_js('UserBoardController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/UserBoardController.js',
	    'deps'=> array('FoowdAPI', 'foowdServices')
	]);

	elgg_define_js('ProducerController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/ProducerController.js',
	    'deps'=> array('FoowdAPI')
	]);	
	/* 
	 * Utility
	 */
	elgg_define_js('Utils', [
	    'src' => '/mod/foowd_theme/lib/js/Utils.js',
	    'deps' => array('elgg')
	]);


}

// -------------------- Da qui in poi sono tutte modifiche apportate da Simone Scardoni

function foowd_theme_register(){
	//----- Tutte le pagine
	// foowd style
	$css_url = 'mod/foowd_theme/lib/css/style.css';
	elgg_register_css('foowd-theme-style', $css_url, 507);


	//<!-- Vendor Style Libraries -->
	$css_url = 'mod/foowd_theme/vendor/animate.css/animate.css';
	elgg_register_css('foowd-theme-animate', $css_url, 507);


	// my foowd-side style: override default elgg where I need
	$css_url = 'mod/foowd_theme/views/css/foowd-main.css';
	elgg_register_css('foowd-theme-main', $css_url, 507);

	//----- jquery ui
	$css_url = '/mod/foowd_theme/views/css/jquery-ui-1.10.4.custom/css/ui-theme-foowd/jquery-ui-1.10.4.custom.css';
	elgg_register_css('jquery.ui.foowd', $css_url, 507);

	//----- wall e producer
	//<!-- Vendor Style Libraries -->
	$css_url = 'mod/foowd_theme/lib/css/reset.css';
	elgg_register_css('foowd-theme-reset', $css_url, 507);


	//----- producer
	$css_url = 'mod/foowd_theme/assets/owl.carusel/owl.carousel.css';
	elgg_register_css('owl-carousel', $css_url, 507);

	$css_url = 'mod/foowd_theme/assets/owl.carusel/owl.theme.css';
	elgg_register_css('owl-theme', $css_url, 507);



}	



function foowd_theme_elgg_manage(){

	// \Fprint::r('lol');
	// 
	//---- topbar
	elgg_register_plugin_hook_handler('register', 'menu:topbar', 'custom_topbarmenu_setup');

	// elgg_register_plugin_hook_handler('register', 'menu:page', 'custom_contentmenu_setup');

	//---- Sidebar
	// rimuovo menu extras: quello coi simboli dei feed
	elgg_register_plugin_hook_handler('register', 'menu:extras', function(){ return '' ;} );

	// elgg_register_plugin_hook_handler('register', 'menu:page', function(){ return '' ; } );
	
	// inutile perche' ho sovrascritto la view owner_block.php
	// rimuovo menu : quello con le pagine, i blog etc. dell'utente
	// elgg_register_plugin_hook_handler('register', 'module:owner_block', function(){ return '' ;} );
	 

}
/*
Email sent to test@test.com
To activate your account, please confirm your email address by clicking on the link we just sent you.
*/


function custom_topbarmenu_setup ($hook, $type, $return, $params) {
    $remove = array('profile'/*, 'friends', 'messages'*/);

    foreach($return as $key => $item) {
        if (in_array($item->getName(), $remove)) {
            unset($return[$key]);
        }
    }

    return $return;
}
