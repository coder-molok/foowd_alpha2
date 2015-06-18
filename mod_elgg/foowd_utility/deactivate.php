<?php

// Considerare eventuali settaggi da elminiare.
// ad esempio i settings dell'utente o dell'amministratore sarebbe saggio NON resettarli.

// Questa parte rimane solo come promemoria

// libero la variabile 
// unset_config('ApiDom'); 		
// elgg.system_message('disattivato');

// tengo traccia della deattivazione nel log
\Uoowd\Logger::addError(basename(dirname(__FILE__)).' e\' stato disattivato');


// mando una mail agli amministratori
 // $options['attribute_name_value_pairs'] = array(
 //                array(
 //                        'name'  => 'admin', 
 //                        'value' => 'yes'
 //                )
 //            );
 // $options['types'] = 'user';    
 // $users = elgg_get_entities_from_attributes($options);


// per funzionare e' necessario che abbia impostato una mail di default dal pannello di amministrazione
// $users = elgg_get_admins();

// foreach($users as $admin){
// 	// var_dump($admin);
// 	echo $admin->email;
// 	$send = elgg_send_email	(	
// 		'Sito Elgg <'.elgg_get_site_entity()->email.'>', // solo il from nella preview, non il vero mittente: original from
// 		'Destinatario Beddo <'.$admin->email.'>', // apparently to
// 		$admin->username.' : notifica',
// 	 	basename(dirname(__FILE__)).': il plugin e\' stato disattivato'
// 	);
// 	// var_dump($send);
// }

