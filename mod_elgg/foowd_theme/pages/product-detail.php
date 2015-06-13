<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Foowd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Vendor Style Libraries -->
    <link href="mod/foowd_theme/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="mod/foowd_theme/vendor/animate.css/animate.css" rel="stylesheet">

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

            <!--[if lt IE 9]>
                <script src="<?php echo $html5shiv_url; ?>"></script>
            <![endif]-->

        <?php

        foreach ($css as $url) {
            echo elgg_format_element('link', array('rel' => 'stylesheet', 'href' => $url));
        }

        ?>
            <!--[if gt IE 8]>
                <link rel="stylesheet" href="<?php echo $ie_url; ?>" />
            <![endif]-->

            <script><?php echo $elgg_init; ?></script>
        <?php
        foreach ($js as $url) {
            echo elgg_format_element('script', array('src' => $url));
        }
      ?>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
    </head>
<body>

<nav class="navbar navbar-default navbar-fixed-top header">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" onClick="utils.goTo()">foowd_</a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li><a id="heart">
            <i class="glyphicon glyphicon-heart fw-menu-icon"></i>
            </a>
        </li>
        <li><a id="userButton"  onClick = "utils.goToUserProfile()">
            <i class="glyphicon glyphicon-user fw-menu-icon"></i>
            </a>
        </li>
      </ul>
  </div>
</nav>

<!--main-->
<div class="container-fluid" id="main">

</div>

<script type="text/javascript">
require(['FoowdAPI', 'ProductDetailController', 'helpers', 'utility-settings'], function(){
  //impostazioni del plugin foowd_utility
  var settings = require('utility-settings');
  //interfaccia alle API di elgg
  var API = require('FoowdAPI');
  //funzioni di elgg
  var elgg = require('elgg');
  //handlebars helpers
  var helpers = require('helpers');
  //controller della pagina
  var ProductDetailController = require('ProductDetailController');
  //funzioni di utilità
  window.utils = require('Utils');
  //aggiungo il base url per le chiamate alle API
  API.setBaseUrl(settings.api);

  ProductDetailController.getDetailsOf('#main');

});
</script>
</body>
</html>