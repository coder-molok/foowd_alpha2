<?php

/**
* Theme for foowd platform
*
* @package foowd
*/


elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {

	//Registro i page handler
	elgg_register_page_handler('activity', 'foowd_wall_page_handler');
}

function foowd_wall_page_handler() {
	if (!include_once(dirname(__FILE__) . "/pages/wall.php"))
		return false;
	return true;
}




