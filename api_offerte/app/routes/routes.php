<?php

// // After instantiation
// $log = $app->getLog();
// $log->warning('Foo'); 
/*
 * API root
 * function without name and use: php closure
 */
$app->group('/api', function() use ($app){

		// gestione offerte
		$app->post('/offers', function() use ($app){

			$returned = new Foowd\FApi\ApiOffer($app, 'post');

		});

		$app->get('/offers', function() use ($app){

			$returned = new Foowd\FApi\ApiOffer($app, 'get');
		
		});


		// gestione utente
		$app->post('/user', function() use ($app){

			$returned = new Foowd\FApi\ApiUser($app, 'post');

		});

		// gestione preferenze
		$app->post('/prefer', function() use ($app){

			$returned = new Foowd\FApi\ApiPrefer($app, 'post');

		});

		$app->get('/prefer', function() use ($app){

			$returned = new Foowd\FApi\ApiPrefer($app, 'get');

		});

});
