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

	// caricamento dei moduli Javascript
	AMD();

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
function foowd_user_preference_page_handler(){
	if(elgg_get_logged_in_user_entity() != 0){
		if (!include_once(dirname(__FILE__) . "/pages/user-preferences.php"))
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
	elgg_define_js('imagesLoaded', [
    	'src' => 'mod/foowd_theme/vendor/imagesloaded/imagesloaded.pkgd.min.js',
	]);
	elgg_define_js('classie', [
    	'src' => 'mod/foowd_theme/lib/grid-loading/js/classie.js',
	]);
	elgg_define_js('animOnScroll', [
    	'src' => 'mod/foowd_theme/lib/grid-loading/js/AnimOnScroll.js',
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
	    'deps'=>array('FoowdAPI','templates', 'elgg', 'page' , 'jquery' , 'bootstrap')
	]);

	elgg_define_js('ProductDetailController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/ProductDetailController.js',
	    'deps'=>array('FoowdAPI','templates', 'elgg', 'handlebars', 'page' , 'jquery' , 'bootstrap')
	]);

	elgg_define_js('NavbarController', [
	    'src' => '/mod/foowd_theme/lib/js/foowd/NavbarController.js',
	    'deps'=>array('FoowdAPI')
	]);
	/* 
	 * Utility
	 */
	elgg_define_js('Utils', [
	    'src' => '/mod/foowd_theme/lib/js/Utils.js',
	    'deps' => array('elgg')
	]);


}
