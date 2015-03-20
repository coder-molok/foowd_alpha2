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
 		 * Richieste Post
 		 * Il body della richiesta sara' in formato json e conterra' almeno le chiavi:
 		 *
 		 * 		type: che rappresenta il metodo della classe da invocare
 		 * 		body: un oggetto che conterra' tutti i parametri da passare ai metodi invocati
 		 *
 		 * Richieste Get
 		 * I parametri saranno automaticamente acquisiti dall'url, ed e' necessaria almeno la chiave
 		 *
 		 * 		type: che rappresenta il metodo della classe da invocare
 		 * 
		 **/
		$app->post('/offers',function() use ($app){

			// attenzione ai nomi: Offer da solo viene sovrascritto dal metodo Offer di propel!
			// eventualmente impiegare degli adeguati namespace
			$returned = new ApiOffer($app, 'post');

		});

		$app->get('/offers',function() use ($app){

			$returned = new ApiOffer($app, 'get');
		
		});

});

// Slim permette di recuperare i parametri della richiesta sull'oggetto $app mediante due metodi:
//
// parametri URL
// 	$app->request()->Params();
// 	
// parametri passati come body (json secondo la convenzione imposta)
//	$app->request()->getBody();
//	
// se la richiesta e' di tipo POST, slim e' in grado di recuperare dati da entrambi i metodi sopra citati.
// 
// se la richiesta e' di tipo GET 
