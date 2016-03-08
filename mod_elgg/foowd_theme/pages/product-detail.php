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
