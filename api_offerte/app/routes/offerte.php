<?php

// // After instantiation
// $log = $app->getLog();
// $log->warning('Foo'); 
/*
 * API root
 * function without name and use: php closure
 */
$app->group('/api', function() use ($app){
	
		/**
 		 * Basic CRUD for the offers
 		 *
 		 *
 		 * la richiesta viene "dirottata" per argomento.
 		 *
 		 * Il body della richiesta sara' in formato json e conterra' almeno le chiavi:
 		 *
 		 * 		type: che rappresenta il metodo della classe da invocare
 		 * 		body: un oggetto che conterra' tutti i parametri da passare ai metodi invocati
 		 *
 		 * Dato che il parametro type tiene conto dell'azione da svolgere, 
 		 * credo che la suddivisione tra get e post possa essere lasciata in disparte:
 		 *
 		 * pertanto tutto opera secondo una REQUEST GENERICA
 		 * 
 		 * 
		 **/
		$app->post('/offers',function() use ($app){

			// attenzione ai nomi: Offer da solo viene sovrascritto dal metodo Offer di propel!
			// eventualmente impiegare degli adeguati namespace
			$returned = new ApiOffer($app);

	});

});

