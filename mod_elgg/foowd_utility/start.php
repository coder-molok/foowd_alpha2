<?php


elgg_register_classes(elgg_get_plugins_path().'foowd_utility/classes');

\Uoowd\Param::checkFoowdPlugins();

include_once(elgg_get_plugins_path().'foowd_utility/functions/exposeAPI.php');

elgg_register_event_handler('init', 'system', 'utility_init');


function utility_init(){
	
	// messaggio d'instabilita'
	$maintenance = elgg_get_plugin_setting('foowdMaintenance', \Uoowd\Param::pid());
	if($maintenance){
		register_error('Il sito potrebbe risultare instabile a causa di lavori di manutenzione.');	
	}
	
	// Inizializzo il wrap della mail e PhpMailer
	// hook all'invio di email
	$mailer = elgg_get_plugin_setting('phpmailer-enable', \Uoowd\Param::pid());
	if($mailer){
		elgg_register_plugin_hook_handler('email', 'system', 'foowd_utility_mail');
	}

	// quando salvo i settings del plugin
	elgg_register_plugin_hook_handler('setting', 'plugin', 'update_json');

	// wrap home pages
	elgg_register_page_handler('cookie-policy','foowd_policy_page_handler');

	// wrap plugin pages
	elgg_register_page_handler('foowd_utility', 'utility_page_handler');

	// registro con la classe Uoowd\Search per svolgere ricerche specifiche
	\Uoowd\FoowdSearch::register();

	// controllo se specifici plugin sono attivati
	new \Uoowd\FoowdNeedleDependencies();

	// cron job
	\Uoowd\FoowdCron::register();
	

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
		case 'testPage':
		    include elgg_get_plugins_path() . 'foowd_utility/test/testPage.php';
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
		case 'checkInit':
		    include elgg_get_plugins_path() . 'foowd_utility/views/default/plugins/foowd_utility/checkInit.php';
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

	$par = $params['params'];

	// per non sovrascrivere l'invio base dei plugin di elgg, 
	// decido che le mail specifiche di foowd sono passate soto forma ti parametro htmlBody e altBody
	if(isset($params['params']['htmlBody'])){
		$bodyHtml = $par['htmlBody'];
	}else{
		$bodyHtml = str_replace(PHP_EOL, '<br/>', $body);
		$bodyHtml = preg_replace('@  @i', ' &emsp;', $bodyHtml);
	}
	
	$bodyAlt = str_replace(PHP_EOL, "\n", $body);
	$bodyAlt = preg_replace('@< *br */? *>@i', "\n", $bodyAlt);

	$mail->Body = $bodyHtml;
	$mail->AltBody = $bodyAlt;//"This is the plain text version of the email content. Yeah!";



	// \Fprint::r($bodyAlt);
	// return false;
	// effettuare redirect
	// \Fprint::r($params['params']);
	if(!$mail->send()){
	    \Uoowd\Logger::addError("Mailer Error: " . $mail->ErrorInfo);
	    // register_error("Errore nell'invio della mail, ci scusiamo per il disagio.");
	    // \Fprint::r( "Message error");
	    return false;
	    // $mail->copyToFolder(); // Will save into inbox
	} 
	else{
	    // $mail->copyToFolder("FoowdDev"); // Will save into Sent folder
	    // \Fprint::r( "Message has been sent successfully");
	    // message_system('Email inviata con successo');
	    return true;
	}
	
		
	// cosa ritornare agli altri hook
	// false evita che vengano eseguiti gli hook successivi (quindi l'invio tramite la funzione mail() di php)
	// $return se voglio che la funzione mail() abbia i parametri per essere inviata
	// return false;
	// return $return;
}


