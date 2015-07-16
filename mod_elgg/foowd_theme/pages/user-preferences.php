<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Foowd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Vendor Style Libraries -->
    <link href="mod/foowd_theme/vendor/animate.css/animate.css" rel="stylesheet">

    <!-- Flavicons (not avaiable yet) -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
    <?php

        // coi seguenti comandi elgg carica l'head proprio come farebbe in una view
        
        $js = elgg_get_loaded_js('head');
        $css = elgg_get_loaded_css();
        $elgg_init = elgg_view('js/initialize_elgg');

        // \Fprint::r($elgg_init);

        $html5shiv_url = elgg_normalize_url('vendors/html5shiv.js');
        $ie_url = elgg_get_simplecache_url('css', 'ie');

        ?>

            <!--[if lt IE 9]>
                <script src="<?php echo $html5shiv_url; ?>"></script>
            <![endif]-->

        <?php

        foreach ($css as $url) {
            echo elgg_format_element('link', array('rel' => 'stylesheet', 'href' => $url));
        }

        ?>
            <!--[if gt IE 8]>
                <link rel="stylesheet" href="<?php echo $ie_url; ?>" />
            <![endif]-->

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
<div id = "account-menu">
    
</div>

<div id="preferences-container">
    
</div>

<script type="text/javascript">
require([
    'Utils',
    'UserBoardController'], function(){
  //utils function
  window.utils = require('Utils');
  //controller
  window.UserBoardController = require('UserBoardController');

});
</script>
</body>
</html>