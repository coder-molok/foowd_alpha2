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

	// wrap home pages
	elgg_register_page_handler('cookie-policy','foowd_policy_page_handler');
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

	// per il plugin crop
	elgg_define_js('crop',[
	    'src' => '/mod/foowd_utility/js/foowd-crop/crop.js',
	    'deps'=> array('jquery', 'elgg', 'imgAreaSelect')
	]);

	elgg_define_js('imgAreaSelect', [
	    'src' => '/mod/foowd_utility/js/imgareaselect/scripts/jquery.imgareaselect.pack.js',
	    'deps' => array('jquery'),
	    // 'exports' => 'jQuery.fn.imgAreaSelect',
	]);

	// gestione del form
	elgg_define_js('foowdFormCheck',[
	    'src' => '/mod/foowd_utility/js/foowd-form-check/foowd-form-check.js',
	    'deps'=> array('jquery', 'elgg')
	]);

	// gestione del form
	elgg_define_js('foowdCropLightbox',[
	    'src' => '/mod/foowd_utility/js/foowd-crop-lightbox/foowd-crop-lightbox.js',
	    'deps'=> array('jquery', 'elgg')
	]);

	// gestione del form
	elgg_define_js('foowdCookiePolicy',[
	    'src' => '/mod/foowd_utility/js/foowd-cookie-policy/foowdCookiePolicy.js',
	    'deps'=> array('jquery')
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
		case 'image-tmp':
		    include elgg_get_plugins_path() . 'foowd_utility/pages/image-tmp.php';
		    break;
		case 'image-path':
		    include elgg_get_plugins_path() . 'foowd_utility/pages/image-path.php';
		    break;
		case 'image-profile':
		    include elgg_get_plugins_path() . 'foowd_utility/pages/image-profile.php';
		    break;
		case 'user-check':
		    include elgg_get_plugins_path() . 'foowd_utility/pages/user-check.php';
		    break;
		default:
			$check = false;
			break;
	}

	return $check;

}


function foowd_policy_page_handler($segments) {
	include elgg_get_plugins_path() . 'foowd_utility/pages/cookie-policy.php';
	return true;
}
