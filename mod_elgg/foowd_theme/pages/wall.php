
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
<div id="wall-container" style="width: 100%;height: 100px;padding-top: 300px;" >

</div>


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
 
