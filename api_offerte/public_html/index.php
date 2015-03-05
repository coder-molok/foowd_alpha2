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
//require_once  '../app/generated-conf/config.php';


$app = new \Slim\Slim();

require '../app/routes/offerte.php';

$app->run();
