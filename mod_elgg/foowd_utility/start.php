<?php


elgg_register_classes(elgg_get_plugins_path().'foowd_utility/classes');

\Uoowd\Param::checkFoowdPlugins();

elgg_register_event_handler('init', 'system', 'utility_init');

function utility_init(){
	
	// $oldGet = $_GET; 
	// var_dump($_GET);
	// $_GET['json']='';
	// include(elgg_get_plugins_path().'foowd_utility/js/pages.php') ;
	// $_GET= $oldGet;
	// var_dump($_GET);


	// quando salvo i settings del plugin
	elgg_register_plugin_hook_handler('setting', 'plugin', 'update_json');

	// wrap plugin pages
	elgg_register_page_handler('foowd_utility', 'utility_page_handler');

	// note the view name does not include ".php"
	// elgg_register_simplecache_view('js/foowd_utility/utility-settings');
	elgg_define_js('utility-settings', [
	    	'src' => \Uoowd\Param::utilAMD(),
	    	// 'deps' => array('jquery')
	]);

	elgg_define_js('page', [
	    	'src' => \Uoowd\Param::pageAMD(),
	]);

}


// hook del salvataggio settings
function update_json($hook, $type, $url, $params){

	// genero un modulo AMD contenente i settings di utility
	$settings = elgg_get_plugin_from_id(\Uoowd\Param::uid())->getAllSettings();
	// unset($settings['tags']);
	
	// rimuovo le chiavi che non voglio condividere mediante js
	$socials = array('Google-Id', 'Google-Secret', 'Facebook-Id','Facebook-Secret');
	foreach($socials as $s) unset($settings[$s]);


	// salvo nel js
	$str = 'define('.json_encode($settings) .');' ;
	if(! file_put_contents(\Uoowd\Param::utilAMD(), $str)) {
		\Uoowd\Logger::addError('Errore nel salvataggio di '.\Uoowd\Param::utilAMD());;
	}
	// var_dump($settings);

	// genero un json di backup dei tags
	if(! file_put_contents(\Uoowd\Param::tags(), $settings['tags']) ){
		\Uoowd\Logger::addError('Errore nel salvataggio di '.\Uoowd\Param::tags());
	};

	// return false;
}


function utility_page_handler($segments) {
	$check = true;

	switch($segments[0]){
		case 'log':
			\Uoowd\Logger::displayLog();
			break;
		case 'test':
		    include elgg_get_plugins_path() . 'foowd_utility/test/test.php';
		    break;
		default:
			$check = false;
			break;
	}

	return $check;

}
