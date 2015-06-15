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
    <link href="mod/foowd_theme/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="mod/foowd_theme/vendor/animate.css/animate.css" rel="stylesheet">

     <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/grid.css">
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
      <div class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" class="form-control" id="searchText" size = "50">
          </div>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li><a id="heart">
            <i class="glyphicon glyphicon-heart fw-menu-icon header-icon"></i>
            </a>
        </li>
        <li><a id="userButton"  onClick = "utils.goToUserProfile()">
            <i class="glyphicon glyphicon-user fw-menu-icon header-icon"></i>
            </a>
        </li>
      </ul>
  </div>
</nav>
</div>
<div class="container-fluid" id="wall-main">
  <div class="wall">
  </div>
</div>
<div class="alert alert-success" role="alert" id="foowd-success"></div>
<div class="alert alert-danger" role="alert" id="foowd-error"></div>
<script type="text/javascript">
require([ 
  'bootstrap', 
  'helpers',
  'Utils',
  'WallController'
  ],function(){
  //helpers di Handlebars
  var helpers = require('helpers'); 
  //funzioni di utility
  window.utils = require('Utils');
  //controller della pagina
  var WallController = require('WallController');
  window.WallController = WallController;
  //richiamo il controller per riempire il wall di prodotti
  WallController.fillWallWithProducts();
});
</script>

</body>