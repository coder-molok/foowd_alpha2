<?php


/**
 * Parte HEAD
 *
 * In questo script per $vars si intende la variabile globale $vars['head'] che utilizzazza elgg.
 *
 * sostanzialmente si occupa di inizializzare i vari elgg_require_js e i load css;
 * istanzia anche i metatag, pertanto vado a integrarla
 *
 * NB: 
 * 	css e js vanno caricati PRIMA di usare le views head e foot
 * 
 */


//----- carico librerie e css di Marco P.
elgg_load_css('foowd-theme-style');
// elgg_load_css('foowd-theme-reset');
// elgg_load_css('foowd-theme-animate');



//----- Default di Simone S.
// css
elgg_load_css('foowd-theme-main');
// js
elgg_require_js('foowd-main');



//----- assign or modify head metatags

$vars['metas']['viewport']['content'] = 'width=device-width, initial-scale=1, maximum-scale=1';
$vars['metas']['description']['content'] = 'Foowd Social E-commerce Site';
// html5 compatibility
$vars['metas']['charset']['name'] = 'charset';
$vars['metas']['charset']['content'] = 'utf-8';


// richiamo la normale head
$head = elgg_view('page/elements/head', $vars);

echo $head;
