<?php

  elgg_load_css('foowd-theme-reset');
  elgg_load_css('foowd-theme-animate');
  elgg_load_css('owl-carousel');
  elgg_load_css('owl-theme');
  
  // custom CSS
  elgg_load_css('foowd-theme-style');

  ob_start();

?>


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

<div id="close-overlay" class="overlay overlay-hugeinc">
  <div class="reverse foowd-navbar">
  </div>
  <nav>
    <ul>
      <li><a target="foowd_site" href="http://www.foowd.it">Sito web</a></li>
      <li><a target="foowd_site" href="http://www.foowd.it/about.html">Su di noi</a></li>
      <li><a target="foowd_site" href="http://www.foowd.it/investors.html">Investitori</a></li>
      <li><a target="foowd_site" href="https://www.smore.com/pcm5x">Produttori</a></li>
      <li><a target="foowd_site" href="http://www.foowd.it/#contatti">Contatti</a></li>
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

<?php
  
  $body = ob_get_contents();
  ob_end_clean();

  echo elgg_view_page('Foowd-Producer', $body, 'foowdThemeFront', $vars);
 

