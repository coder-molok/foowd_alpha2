<?php

/*
 * Foowd Wall Page
 * 
 */

//Carico Mustache tramite l'autoload di composer
require 'mod/foowd_theme/vendor/autoload.php';
Mustache_Autoloader::register();
//Inizializzo la template engine indicando la directory in cui si trovano i template
//modifico l'estensione dei template, in modo da poter utilizzare dei file HTML
$options =  array('extension' => '.html');
$mustache = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates', $options),
));
//titolo della pagina
$title = "Foowd Wall";

//Carico e compilo il template dell'anteprima del prodotoo
$productTemplate = $mustache->loadTemplate('product');
//Creo il contesto di dati da inserire nel template
$context = array(
		'name'=>'Pizza Margherita',
		'price'=>'6',
		'thumb'=>'http://lorempizza.com/380/240',
		'description'=>'La pizza Margherita è la mia preferita'
);
//associo al template il contesto caricato e lo aggiungo al contenuto della pagina
$content = $productTemplate->render($context);

//Carico la view di elgg
// $body = elgg_view_layout('one_column', array(
//     'content' => $content,
// ));
$body = $content;
//Stampo il contenuto della pagina
echo elgg_view_page($title,$body);