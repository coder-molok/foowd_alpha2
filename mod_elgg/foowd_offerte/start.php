<?php

/*
 * Foowd Offers Plugin
 * - Custom page with offers
 * - Menù entry to access to the offers page
 */


elgg_register_event_handler('init', 'system', 'offer_init');

function offer_init() {

	//register a new page handler
	elgg_register_page_handler('foowd_offerte', 'offerte_page_handler');

	//Add a menu item to the site menu
	elgg_register_menu_item('site', ElggMenuItem::factory(array(
		'name' => 'offerte',
		'href' => '/foowd_offerte/all',
		'text' => elgg_echo('Offerte'),
	)));

}

function offerte_page_handler($segments) {
	if ($segments[0] == 'all') {
		include elgg_get_plugins_path() . 'foowd_offerte/pages/foowd_offerte/all.php';
		return true;
	}
	return false;
}