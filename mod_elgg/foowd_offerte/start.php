<?php

elgg_register_event_handler('init', 'system', 'offerte_init');


$p = \Foowd\Param::apiDom();

$api = get_config('ApiDom');

function offerte_init() {

	//register a new page handler
	elgg_register_page_handler('foowd_offerte', 'offerte_page_handler');

	// registro l'azione da avviare al submit del form presente in foowd_offerte/add
	// eventualmente con un plugin_hook('action', 'foowd_offerte/add', callback)
	// vado a intercettare il submit del form.
	// Se la callback ritorna true, allora il sistema procede con la sua routine action (quella che ho impostato qui sotto), 
	// altrimenti con un return false la callback blocca azioni associate a quella action.
	elgg_register_action("foowd_offerte/add", elgg_get_plugins_path() . 'foowd_offerte/actions/foowd_offerte/add.php');


	//Add a menu item to the site menu
	// elgg_register_menu_item('site', ElggMenuItem::factory(array(
	// 	'name' => 'offerte',
	// 	'href' => '/foowd_offerte/all',
	// 	'text' => elgg_echo('Offerte'),
	// )));

}

function offerte_page_handler($segments) {
	$check = true;

	switch($segments[0]){
		case 'all':
			include elgg_get_plugins_path() . 'foowd_offerte/pages/foowd_offerte/all.php';
			break;
		case 'add':
			include elgg_get_plugins_path() . 'foowd_offerte/pages/foowd_offerte/add.php';
			break;
		case 'success':
			include elgg_get_plugins_path() . 'foowd_offerte/pages/foowd_offerte/success.php';
			break;
		default:
			$check = false;
			break;
	}

	return $check;
}
