<?php

// controllo che i plugin foowd siano attivi
// foreach( new \DirectoryIterator(elgg_get_plugins_path()) as $plug){
// 	if(preg_match('@foowd_@', $plug->getFilename())){
// 		// il filename coincide col plug id
// 		$state = elgg_is_active_plugin($plug->getFilename());
// 		if(!is_object($state)){
// 			register_error('Plugin '.$plug->getFilename().' non attivo');
// 		} 
// 	} 
// }

elgg_register_classes(elgg_get_plugins_path().'foowd_utility/classes');



elgg_register_event_handler('init', 'system', 'utility_init');

function utility_init(){

	// quando salvo i settings del plugin
	elgg_register_plugin_hook_handler('setting', 'plugin', 'salva_json');

}



function salva_json($hook, $type, $url, $params){
	$tag['tags'] = $params['value'];
	$tag = json_encode($tag);
	file_put_contents(\Uoowd\Param::tags(), $tag);
	// return false;
}

