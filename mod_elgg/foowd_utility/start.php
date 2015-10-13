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

	// Inizializzo il wrap della mail e PhpMailer
	// hook all'invio di email
	// elgg_register_plugin_hook_handler('email', 'system', 'foowd_utility_mail');

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

	// gestione del form
	elgg_define_js('foowdServices',[
	    'src' => '/mod/foowd_utility/js/foowd-services.js',
	    'deps'=> array('elgg', 'page', 'jquery')
	]);

	// Elgg di default carica jquery-ui,
	// ma uitlizzando i plugin di jquery-ui in versione AMD, alcuni richiedono esplicitamente
	// un require ['jquery.ui']
	// per non modificare manualmente i plugin di terze parti, creo un finto modulo jquery.ui
	// perche' tanto quello vero e' gia' caricato e inizializzato a $.ui
	
	elgg_define_js('jquery.ui',[
	    'src' => '/mod/foowd_utility/js/faker-jquery-ui.js',
	    'deps'=> array('jquery')
	]);

	// gestione del form
	elgg_define_js('jquery.datetimepicker',[
	    'src' => '/mod/foowd_utility/bower_components/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.js',
	    'deps'=> array('jquery', 'jquery.ui'),
	    // 'exports' => 'jQuery.fn.datetimepicker'
	]);

	elgg_register_css('jquery.datetimepicker', '/mod/foowd_utility/bower_components/jqueryui-timepicker-addon/src/jquery-ui-timepicker-addon.css');
	
	
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
		// case 'change-profile-name':
		//     include elgg_get_plugins_path() . 'foowd_utility/test/change-profile-name.php';
		//     break;
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
		case 'services':
		    include elgg_get_plugins_path() . 'foowd_utility/pages/services.php';
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


function foowd_utility_mail($hook, $type, $return, $params){

	// \Uoowd\Logger::error(func_get_args());
	// error_log(json_encode( func_get_args() ));

	$adrs = $return['to'];
	$subj = $return['subject'];
	$body = $return['body'];

	$mail = new \Uoowd\FoowdMailer();
	$mail->addAddress($adrs, "Recepient Name");
	$mail->Subject = $subj;//"Subject Text";
	$mail->Body = $body;
	$mail->AltBody = "This is the plain text version of the email content. Yeah!";

	// effettuare redirect
	// if(!$mail->send()) 
	// {
	//     \Fprint::r("Mailer Error: " . $mail->ErrorInfo);
	//     // $mail->copyToFolder(); // Will save into inbox
	// } 
	// else 
	// {
	//     // $mail->copyToFolder("FoowdDev"); // Will save into Sent folder
	//     \Fprint::r( "Message has been sent successfully");
	// }
	
		
	// cosa ritornare agli altri hook
	// false evita che vengano eseguiti gli hook successivi (quindi l'invio tramite la funzione mail() di php)
	// $return se voglio che la funzione mail() abbia i parametri per essere inviata
	// return false;
	return $return;
}


// see https://github.com/markharding/elgg-web-services-deprecated/blob/master/lib/user.php
// elgg_ws_expose_function("foowd.users.active",
//                 "count_active_users",
//                  array("minutes" => array('type' => 'int',
//                                           'required' => false),
//                  		'greeting' => array(
//                  		                        'type' => 'string',
//                  		                        'required' => false,
//                  		                        'default' => 'Hello',
//                  		                        'description' => 'Greeting to be used, e.g. "Good day" or "Hi"',
//                  		                    )
//                  ),
//                  'Number of users who have used the site in the past x minutes',
//                  'GET',
//                  false,
//                  false
//                 );

function count_active_users($minutes=10) {
    $seconds = 60 * $minutes;
    $count = count(find_active_users($seconds, 9999));
    $count = array('count'=>'count', 'mio'=>'random');
    return $count;
}