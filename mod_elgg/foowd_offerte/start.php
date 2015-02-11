<?php

elgg_register_event_handler('init', 'system', 'offer_init');

function offer_init() {

	// add a site navigation item
	$item = new ElggMenuItem('offer', elgg_echo('offer:offers'), 'foowd_offerte/all');
	elgg_register_menu_item('site', $item);


	elgg_register_page_handler('foowd_offerte', 'offerte_page_handler');
	function offerte_page_handler($segments) {
		if ($segments[0] == 'all') {
			include elgg_get_plugins_path() . 'foowd_offerte/pages/foowd_offerte/all.php';
			return true;
		}
		return false;
	}

}

