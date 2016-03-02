<?php

	// header('Content-Type: application/javascript');

	// genero un modulo AMD contenente i settings di utility
	$settings = elgg_get_plugin_from_id(\Uoowd\Param::uid())->getAllSettings();
	// unset($settings['tags']);
	

	// NB: Senza la rimozione salverei TUTTI i dati, quindi risulta utile per eventuali backup
	// rimuovo le chiavi che non voglio condividere mediante js
	$remove = array('Google-Id', 'Google-Secret', 'Facebook-Id','Facebook-Secret');
	foreach($remove as $s) unset($settings[$s]);
	// rimuovo i dati di php mailer!
	foreach($settings as $k => $v) if(preg_match('@phpmailer-@', $k)) unset($settings[$k]);


	// salvo nel js
	$str = 'define('.json_encode($settings) .');' ;
	
	echo $str;
	
?>
