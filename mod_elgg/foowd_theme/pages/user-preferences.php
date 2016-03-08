<?php

  elgg_load_css('foowd-theme-animate');
  elgg_load_css('foowd-theme-style');
  
  ob_start();

?>

<div class="foowd-navbar">
</div>
<div id = "account-menu">
</div>
<div id="preferences-container">
</div>


<!-- ############################################### -->

<script type="text/javascript" src="mod/foowd_theme/vendor/modernizr/modernizr.js"></script>
<script type="text/javascript">
require(['Utils', 'UserBoardController'], function(){
  window.UserBoardController = require('UserBoardController');
  window.UserBoardController.init();

});
</script>


<?php
  
  $body = ob_get_contents();
  ob_end_clean();

  echo elgg_view_page('Foowd-Preferences', $body, 'foowdThemeFront', $vars);
 
