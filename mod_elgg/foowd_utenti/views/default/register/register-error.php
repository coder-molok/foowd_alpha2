<?php

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$title = "Errore Registrazione";

// start building the main column of the page
// $content = elgg_view_title($title);
$content = "<h3>$title</h3>";

//$content .= elgg_view('foowd_offerte/add', array(), $vars);
$content .= '<p>&Egrave; avvenuto un errore di registrazione: si prega di riprovare tra qualche minuto.</p>';

// se non e' impostata nel pannello di configurazione generale del sito, allora recupero quella settata con phpMailer
$mail = (elgg_get_config('siteemail') != '') ? elgg_get_config('siteemail') : elgg_get_plugin_setting('phpmailer-from', \Uoowd\Param::uid());


$site = elgg_get_config('sitename');

$content .= "<p>Qualora l'errore dovesse persistere &egrave; possibile contattare i gestori all'indirizzo <a href=\"mailto:$mail?Subject=Problema registrazione sito - $site - \" target=\"_top\">$mail</a>.</p>";

$body = $content;

$body = '<div class="elgg-main foowd-page-registration-error">'.$body.'</div>';

// draw the page
echo elgg_view_page($title, $body);
