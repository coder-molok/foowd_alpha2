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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
<body>

<nav class="navbar navbar-fixed-top header">
    <div class="container-fluid">
        <div class="navbar-header navbar-menu">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ordina per: <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                        <li><a onClick = "foowd.filterBy('date')">Data</a></li>
                        <li><a onClick = "foowd.filterBy('price')">Prezzo</a></li>
                  </ul>
                </li>
            </ul>
            <div class="navbar-form navbar-left" role="search">
              <div class="form-group">
                <a class="navbar-brand" href = <?php echo($_SERVER[HTTP_ROOT]);?>>foowd_</a>
                <input type="text" id ="searchText"class="form-control">
              </div>
              <button onclick = "foowd.searchOffers()" class="btn btn-default">Submit</button>
            </div>
        </div>
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a><i class="glyphicon glyphicon-heart fw-menu-icon"></i></a></li>
            <li><a href=<?php echo($_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]."login");?>>
                <i class="glyphicon glyphicon-user fw-menu-icon"></i>
                </a>
            </li>
        </ul>
    </div>
    </div>
</nav>

<!--main-->
<div class="container-fluid" id="main">
     <div class="center-block">
            <img src="http://lorempixel.com/64/64/people" class = "img-responsive img-circle">
            <div id = "details">
                Pippo Pluto
            </div>
     </div>
    <div id = "user-details" class = "row">
       
    </div>
    <ul id="user-preferences" class = "media-list">
        <!-- Viene Riempito dal Javascript -->
    </ul>
</div>

<!-- Javascripts -->

<!-- Vendor Libraries -->
<script type='text/javascript' src="mod/foowd_theme//vendor/jquery/dist/jquery.min.js"></script>
<script type='text/javascript' src="mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="mod/foowd_theme/vendor/handlebars/handlebars.runtime.js"></script>

<!-- Pre-Compiled Templates -->
<script type="text/javascript" src="mod/foowd_theme/pages/templates/templates.js"></script>

<!-- Custom Libraries -->
<script type="text/javascript" src="mod/foowd_theme/lib/js/foowd.js"></script>

<!-- Load the wall -->
<script type="text/javascript">

    document.addEventListener('DOMContentLoaded',function(event){

        //prendo il parametro per richiamare le API
        var apiUrl = <?php echo json_encode(elgg_get_plugin_setting('api', \Uoowd\Param::uid()))?>;
        //prendo l'id dell'utente. 0 equivale a non loggato.
        var userId = <?php echo json_encode(elgg_get_logged_in_user_guid())?>;
        //imposto i parametri nel modulo
    
        foowd.setUserId(userId);
        foowd.setBaseUrl(apiUrl);
        //richiamo la procedura per mostrare il wall
        foowd.getProducts();
        
    });
    
    
</script>

<!-- JavaScript jQuery code from Bootply.com editor  -->
<script type='text/javascript' src="mod/foowd_theme/lib/js/toggle-layout.js"></script>

<!-- Google analytics settings -->
<script type="text/javascript" src="mod/foowd_theme/lib/js/google-analytics.js"></script>

<!--<div class="ad collapse in">
    <button class="ad-btn-hide" data-toggle="collapse" data-target=".ad">&times;</button>
    <script async type="text/javascript" src="//cdn.carbonads.com/carbon.js?zoneid=1673&serve=C6AILKT&placement=bootplycom" id="_carbonads_js"></script>
</div>-->

</body>
</html>