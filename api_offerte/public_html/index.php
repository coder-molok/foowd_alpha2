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


require '../app/vendor/autoload.php';

// richiedo il file di configurazione per poter svolgere le connessioni
require_once  '../app/data/generated-conf/config.php';

// Configuro un logger
// http://symfony.com/it/doc/current/cookbook/logging/monolog.html
date_default_timezone_set('Europe/Rome');
$logger = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
	'name' => 'FoowdLogger',
    'handlers' => array(
        new \Monolog\Handler\RotatingFileHandler('../log/api.log'),
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
    'log.writer'    => $logger,
    'debug'         => false,   // disabilitato per poter usare il mio personale error handler
));

// ritorno gli errori in formato json
$app->error(function (\Exception $e) use ($app) {
    // http://www.xml.com/pub/a/2004/12/01/restful-web.html
    switch ($app->request->getMethod()) {
        case 'GET':
            $code = 200;
            break;
        case 'POST':
            $code = 201;
            break;
        default:
            $code = 'Not Found Method';
            break;
    }
    $errors['status'] = $code;
    $errors['msg'] = $e->getMessage();
    echo json_encode(array('errors'=>$errors));   
});


require '../app/routes/offerte.php';

$app->run();