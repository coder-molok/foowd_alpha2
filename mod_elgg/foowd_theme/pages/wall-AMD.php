<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Foowd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Vendor Style Libraries -->
    <link href="mod/foowd_theme/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="mod/foowd_theme/vendor/animate/animate.css" rel="stylesheet">

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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
    </head>
<body>

<nav class="navbar navbar-fixed-top header">
    <div class="container-fluid">
        <div class="navbar-header navbar-menu">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ordina per: <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                        <!-- <li><a onClick = "foowd.filterBy('date')">Data</a></li>
                        <li><a onClick = "foowd.filterBy('price')">Prezzo</a></li> -->
                  </ul>
                </li>
            </ul>
            <div class="navbar-form navbar-left" role="search">
              <div class="form-group">
                <a class="navbar-brand" href = "">foowd_</a>
                <input type="text" id ="searchText"class="form-control">
              </div>
              <!-- <button onclick = "foowd.searchOffers()" class="btn btn-default">Submit</button> -->
            </div>
        </div>

    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a id="heart" href = "">
                <i class="glyphicon glyphicon-heart fw-menu-icon"></i>
                </a>
            </li>
            <li><a id="userButton" href = "" >
                <i class="glyphicon glyphicon-user fw-menu-icon"></i>
                </a>
            </li>
        </ul>
    </div>
    </div>
</nav>

<!--main-->
<div class="container" id="main">
    <div class="wall">
        <!-- Viene Riempito dal Javascript -->
    </div>
</div>

<!-- Javascripts -->

<!-- Vendor Libraries -->
<!-- <script type='text/javascript' src="mod/foowd_theme//vendor/jquery/dist/jquery.min.js"></script> -->
<!-- <script type='text/javascript' src="mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js"></script> -->
<!-- <script type="text/javascript" src="mod/foowd_theme/vendor/handlebars/handlebars.runtime.js"></script> -->

<!-- Pre-Compiled Templates -->
<!-- <script type="text/javascript" src="mod/foowd_theme/pages/templates/templates.js"></script> -->

<script type="text/javascript">

    // require(["elgg\/dev"]);
    require(['foowd'], function(){

      // modulo AMD definito nella sezione js di foowd_utility: 
      // tale modulo si aggiorna ogni volta che i settings vengono salvati
      var settings = require('utility-settings');

      // il modulo foowd
      var foowd = require('foowd');

      //  il modulo elgg: praticamente l'analogo dei comendi elgg_ in php
      var elgg = require('elgg');
      // console.log(elgg)

      // utente elgg
      var user = elgg.get_logged_in_user_entity();
      var UserId = 0;
      if(user) UserId = user.guid;

       
      //imposto i parametri nel modulo
      foowd.setBaseUrl(settings.api);
      foowd.setUserId(UserId);

      if(!UserId) UserId = 0;

      //richiamo la procedura per mostrare il wall
      foowd.getProducts();   


      // in questo oggetto si potrebbero inserire tutti i links del sito
      var links = (function(){
        var site = elgg.get_site_url();
        console.log(site);
        var login = site + 'login';
        var profile = site + 'profile';
        return {
          'profile' : profile,
          'login' : login
        };
      })();

      // imposto il redirect dell'icona utente
      var userButton = document.getElementById('userButton');

      // inizializzo il link, ma in concreto sarebbe superfluo
      setUserButton(userButton);
      
      // azione al click
      userButton.onclick=function(event){
        setUserButton(this);
        // event.preventDefault();
      };

      // seleziono il link opportuno
      function setUserButton(obj){
        if(elgg.is_logged_in()){
          obj.href = links.profile;
        }else{
          obj.href = links.login;
        }
      }

   });
 
    
</script>
<!-- Custom Libraries -->
<!-- <script type="text/javascript" src="mod/foowd_theme/lib/js/foowd.js"></script> -->

<!-- Load the wall -->

<!-- JavaScript jQuery code from Bootply.com editor  -->
<!-- <script type='text/javascript' src="mod/foowd_theme/lib/js/toggle-layout.js"></script> -->

<!-- Google analytics settings -->
<script type="text/javascript" src="mod/foowd_theme/lib/js/google-analytics.js"></script>

<!-- <div class="ad collapse in">
    <button class="ad-btn-hide" data-toggle="collapse" data-target=".ad">&times;</button>
    <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?zoneid=1673&serve=C6AILKT&placement=bootplycom" id="_carbonads_js"></script>
</div> -->

</body>
</html>