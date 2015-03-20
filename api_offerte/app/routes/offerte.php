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
 		 * la richiesta viene "dirottata" per argomento.
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
