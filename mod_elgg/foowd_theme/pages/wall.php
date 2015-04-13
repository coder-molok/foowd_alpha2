<?php

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
// $template = 'Hello, {{name}},<br /> Today is {{dayoftheweek}}, and the time is {{currentime}}';
// //set the template values
// $values = array(
//     'name'=>'John',
//     'dayoftheweek'=>date('l'),
//     'currentime'=>date('H:i:s')
// );

// //start the mustache engine
// $m = new Mustache_Engine;
// //render the template with the set values
// echo $m->render($template, $values);
require 'mod/foowd_theme/vendor/autoload.php';
elgg_load_css('bootstrap_css');
Mustache_Autoloader::register();

$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
));

$title = "Foowd Wall";

//Compile Product Template

$productTemplate = $mustache->loadTemplate('product');

$context = array(
		'name'=>'Pizza Margherita',
		'price'=>'6',
		'thumb'=>'http://lorempizza.com/380/240',
		'description'=>'La pizza Margherita è la mia preferita'
);


$content = $productTemplate->render($context);

// // start building the main column of the page
// $content = elgg_view_title($title);

// // layout the page
$body = elgg_view_layout('one_column', array(
    'content' => $content,
));

// // draw the page
echo elgg_view_page($title, $body);