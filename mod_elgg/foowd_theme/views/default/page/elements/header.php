<!-- <div class="foowd-header">foowd_</div> -->

<!-- <div class="foowd-navbar">
</div> -->

<script type="text/javascript">
require([ 
	'jquery',
  	'NavbarController',
  ],function(){

    require('NavbarController').loadNavbar();
    var $ = require('jquery');

    $('.foowd-navbar').css({
    	// 'position':'relative',
    	// 'z-index': '-1'
    });

});
</script>

<?php
// link back to main site.
// echo elgg_view('page/elements/header_logo', $vars);

// drop-down login
// echo elgg_view('core/account/login_dropdown');

// echo '<div class="elgg-heading-site"><a href="'.elgg_get_site_url().'" style="color:white;position:relative; top: -10px;">Home</a></div>';

 
