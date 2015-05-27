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
