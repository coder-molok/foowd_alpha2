<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
#require 'Slim/Slim.php';

#\Slim\Slim::registerAutoloader();


require '/../app/vendor/autoload.php';

// richiedo il file di configurazione per poter svolgere le connessioni
require_once  '../app/data/generated-conf/config.php';

// Configuro un logger
// http://symfony.com/it/doc/current/cookbook/logging/monolog.html
date_default_timezone_set('Europe/Rome');
$logger = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
	'name' => 'FoowdLogger',
    'handlers' => array(
        new \Monolog\Handler\StreamHandler('../logs/'.date('Y-m-d').'.log'),
    ),
    'processors' => array(
     	        	function ($record) {
     	        		
     				    $record['extra']['extra'] = 'Debug';
     				    $record['context']['API'] = 'Foowe';
     					
     				    return $record;
     				},
     	        ),
));


// avvio slim con le dovute configurazioni niziali
$app = new \Slim\Slim(array(
    'log.writer' => $logger,
));


require '../app/routes/offerte.php';

$app->run();
