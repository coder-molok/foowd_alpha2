<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Foowd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Vendor Style Libraries -->
    <link href="mod/foowd_theme/lib/css/reset.css">
    <!-- Vendor Style Libraries -->
    <link href="mod/foowd_theme/vendor/animate.css/animate.css" rel="stylesheet">
     <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
    <!-- Flavicons (not avaiable yet) -->
    <!-- elgg -->
    <?php

        // coi seguenti comandi elgg carica l'head proprio come farebbe in una view
        
        $js = elgg_get_loaded_js('head');
        $css = elgg_get_loaded_css();
        $elgg_init = elgg_view('js/initialize_elgg');

        // \Fprint::r($elgg_init);

        $html5shiv_url = elgg_normalize_url('vendors/html5shiv.js');
        $ie_url = elgg_get_simplecache_url('css', 'ie');

        ?>

            

        <?php

        foreach ($css as $url) {
            echo elgg_format_element('link', array('rel' => 'stylesheet', 'href' => $url));
        }

        ?>
            

            <script><?php echo $elgg_init; ?></script>
        <?php
        foreach ($js as $url) {
            echo elgg_format_element('script', array('src' => $url));
        }
      ?>
</head>
<body>
<div class="foowd-navbar">
  
</div>

<ul class="grid effect-1" id="wall">

</ul>

<div class="alert alert-success" role="alert" id="foowd-success"></div>
<div class="alert alert-danger" role="alert" id="foowd-error"></div>
<script type="text/javascript" src="mod/foowd_theme/vendor/modernizr/modernizr.js"></script>
<script type="text/javascript">
require([ 
  'bootstrap', 
  'helpers',
  'templates',
  'Utils',
  'masonry',
  'imagesLoaded',
  'classie',
  'animOnScroll',
  'WallController'
  ],function(){

  var imagesLoaded = require('imagesLoaded');
  window.imagesLoaded = imagesLoaded;
  var masonry = require('masonry');
  window.Masonry = masonry;
  var classie = require('classie');
  console.log(classie);
  window.classie = classie;

  //helpers di Handlebars
  var helpers = require('helpers'); 
  //templates di handlebars
  var templates = require('templates');
  //funzioni di utility
  window.utils = require('Utils');
  //controller della pagina
  var WallController = require('WallController');
  window.WallController = WallController;
  //inserisco la barra di navigazione
  $('.foowd-navbar').html(templates.searchNavbar(""));
  //richiamo il controller per riempire il wall di prodotti
  WallController.fillWallWithProducts();
});
</script>

</body>