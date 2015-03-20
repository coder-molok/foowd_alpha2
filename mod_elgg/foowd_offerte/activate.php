<?php

// all'attivazione controllo che il nome scelto per lo stoccaggio
// 		non sia gia' presente tra i parametri di configurazione
// 		altrimenti rischierei di sovrascrivere parametri del core o 
// 		di altri plugin.

$check = get_config('ApiDom');

if(is_null($check) || empty($check)){
	// Utilizzo momentaneo della classe Param: se inutile lo cancellero'
	elgg_save_config( 'ApiDom' , 'http://localhost/api_offerte/public_html/api/');
	elgg.system_message('plugin attivato con successo');
}else{
	elgg.register_error(elgg_echo('Non posso attivare il plugin'));
	$landing_plugin = new ElggPlugin('foowd_offerte');
	$landing_plugin->deactivate();
}

// promemoria personale
// elgg_register_event_handler('activate', 'plugin', 'check_config');
// function check_config(){
// 	return false;
// }

