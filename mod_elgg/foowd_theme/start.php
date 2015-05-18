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
}

function foowd_wall_page_handler() {
	if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
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


