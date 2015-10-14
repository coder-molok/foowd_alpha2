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
