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
	elgg_register_page_handler('panel','foowd_user_wall_page_handler');

	// parte aggiunta da Simone Scardoni
	AMD();

}

function foowd_wall_page_handler() {
	// if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
	if (!include_once(dirname(__FILE__) . "/pages/wall-AMD.php"))
		return false;
	return true;
}

function foowd_user_wall_page_handler() {
	if(elgg_get_logged_in_user_guid() != 0){
		if (!include_once(dirname(__FILE__) . "/pages/user-wall.php"))
			return false;
		return true;
	}
	forward("");
}


// AMD LOAD
function AMD(){
	
	elgg_define_js('foowd', [
	    // 'src' => '/mod/foowd_theme/views/default/js/foowd_theme/foowd.js',
	    'src' => '/mod/foowd_theme/lib/js/foowd-AMD.js',
	    'deps'=>array('templates', 'elgg', 'handlebars', 'utility-settings' )
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


	elgg_define_js('templates', [
	    	'src' => '/mod/foowd_theme/pages/templates/templates-amd.js',
	    	// 'deps'=> array('handlebars')
	]);

	elgg_define_js('bootstrap', [
	    	'src' => 'mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js',
	    	'deps' => array('jquery')
	]);

}
