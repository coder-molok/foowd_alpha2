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
	'name' => 'ApiFoowd',
    'handlers' => array(
        new \Monolog\Handler\RotatingFileHandler(__DIR__.'/../log/api.log'),
    ),
    'processors' => array(
     	        	function ($record) {
     	        		
     				    $record['extra']['extra'] = 'Debug';
     				    $record['context']['API'] = 'Foowd';
     					
     				    return $record;
     				},
     	        ),
));


// avvio slim con le dovute configurazioni niziali
$app = new \Slim\Slim(array(
    'log.writer'    => $logger,
    'debug'         => false,   // disabilitato per poter usare il mio personale error handler
));


//------------------------------------------------------------------ inizio gestione LOG ERRORI
// evito di visualizzare gli errori
ini_set('display_errors', '0');

// ritorno e salvo in formato json gli errori dovuti alle eccezioni 
$app->error(function (\Exception $e) use ($app) {
    // http://www.xml.com/pub/a/2004/12/01/restful-web.html
    switch ($app->request()->getMethod()) {
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
    //var_dump($e);
    $errors['status'] = $code;
    $errors['msg'] = $e->getMessage();
    $errors['line'] = $e->getLine();
    $errors['file'] = $e->getFile();
    $app->getLog()->error(json_encode($errors));
    echo json_encode(array('errors'=>$errors, "response"=>false));   
});

// ritorno e salvo in formato json i Fatal Error
register_shutdown_function(function() use($app){
    if (null === $lastError = error_get_last()) {
        return;
    }

    $errors = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING, E_STRICT);
    if (in_array($lastError['type'], $errors)) {
        // $e = new \ErrorException(
        //     @$lastError['message'], @$lastError['type'], @$lastError['type'],
        //     @$lastError['file'], @$lastError['line']
        // );
        //$msg = 'Fatal error at line '.$e->getLine();
        
        $lastError['request'] = $app->request()->getMethod();
        $lastError['msg'] = 'Fatal Error (system): '.$lastError['message'];
        unset($lastError['message']);

        $app->getLog()->error(json_encode($lastError));
        echo json_encode(array("errors"=>$lastError, "response"=>false));
    }

});

$app->notFound(function () use ($app) {
    $lastError['request'] = $app->request()->getMethod();
    $lastError['msg'] = 'Route '.$app->request()->getResourceUri().' not Found';
    $lastError['response'] = false;
    //var_dump($app->request());
    $app->getLog()->error(json_encode($lastError));
    echo json_encode(array("errors"=>$lastError, "response"=>false));
});
//------------------------------------------------------------------ fine gestione LOG ERRORI


// classe creata per aggiungere un controllo di sicurezza
// se non sono in localhost, verifico l'attendibilita' della sorgente:
// opzione mantenuta per poter continuare a utilizzare postman
// if($app->request()->headers('Host') !== 'localhost'){
//     $app->add(new Foowd\HttpBasicAuth());
// }


require '../app/routes/routes.php';

$app->run();

