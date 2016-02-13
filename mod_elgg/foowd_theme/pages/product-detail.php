<?php

  elgg_load_css('foowd-theme-animate');
  elgg_load_css('foowd-theme-style');

  ob_start();

?>

<div class="foowd-navbar">
</div>

<!--main-->
<div id="product-detail-main">
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
require(['ProductDetailController', 'helpers', 'templates'], function(){
  window.ProductDetailController = require('ProductDetailController');
  window.ProductDetailController.init();
});
</script>

<?php
  
  $body = ob_get_contents();
  ob_end_clean();

  echo elgg_view_page('Foowd-Details', $body, 'foowdThemeFront', $vars);
