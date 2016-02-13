
<?php
  // css specifici
  elgg_load_css('foowd-theme-reset');


  // catturo l'output
  ob_start();
?>

<div class="foowd-navbar">
</div>
<ul class="grid effect-1" id="wall">

</ul>
<!-- server per il loader-->
<div id="wall-container"style="width: 100%;height: 100px;padding-top: 300px;" >

</div>
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
  'jquery-loading-overlay',
  'WallController',
  'NavbarController',
  ],function(){

  window.imagesLoaded = require('imagesLoaded');
  window.Masonry = require('masonry');
  window.classie = require('classie');

  window.WallController = require('WallController');
  window.WallController.init();

});
</script>

<?php
  
  $body = ob_get_contents();
  ob_end_clean();

  echo elgg_view_page('Foowd Home', $body, 'foowdThemeFront', $vars);
 
