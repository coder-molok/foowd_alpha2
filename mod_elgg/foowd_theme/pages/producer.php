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
    <link href="mod/foowd_theme/assets/owl.carusel/owl.carousel.css" rel="stylesheet">
    <link href="mod/foowd_theme/assets/owl.carusel/owl.theme.css" rel="stylesheet">
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
<div id="producer-container">
  <div id = "producer-profile">
  </div>
  <div id = "producer-products">
    <div class="about">I nostri prodotti</div>
  </div>
</div>
<ul class="grid effect-1" id="producer-wall">

</ul>
<!-- Pezzo che deve essere comune a tutte le pagine -->

<div class="overlay overlay-hugeinc">
  <div class="reverse foowd-navbar">
  </div>
  <nav>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Work</a></li>
      <li><a href="#">Clients</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>
</div>

<!-- ############################################### -->

<!-- Pezzo che deve essere presente se viene aggiuntao tolta un preferenza -->

<div class="foowd-alert" role="alert" id="foowd-success"></div>
<div class="foowd-alert" role="alert" id="foowd-error"></div>

<!-- ############################################### -->

<script type="text/javascript" src="mod/foowd_theme/vendor/modernizr/modernizr.js"></script>
<script type="text/javascript" src="mod/foowd_theme/assets/owl.carusel/owl.carousel.min.js"></script>
<script type="text/javascript">
require([  
  'helpers',
  'masonry',
  'imagesLoaded',
  'classie',
  'animOnScroll',
  'ProducerController',
  ],function(){

  window.imagesLoaded = require('imagesLoaded');
  window.Masonry = require('masonry');
  window.classie = require('classie');

  window.ProducerController = require('ProducerController');
  window.ProducerController.init();

});
</script>


</body>