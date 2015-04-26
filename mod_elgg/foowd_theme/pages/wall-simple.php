<!DOCTYPE html>
<html lang="it">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Foowd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Vendor Style Libraries -->
    <link href="mod/foowd_theme/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Flavicons (not avaiable yet) -->

    <!-- Custom CSS -->
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/style.css">
    <link rel="stylesheet" href="mod/foowd_theme/lib/css/ads.css">
</head>

<body>

<nav class="navbar navbar-fixed-top header">
    <div class="container-fluid">
        <div class="navbar-header navbar-menu">
            <a href="" class="navbar-brand">filtra per:</a>
            <ul class="nav navbar-nav">
                <li><a href="">Visualizzazioni</a></li>
                <li><a href="">Data</a></li>
                <li><a href="">Prezzo</a></li>
            </ul>
        </div>
    </div>
    <div class="container-fluid navbar-menu">
        <div class="collapse navbar-collapse">
        
            <ul class="nav navbar-nav navbar-right">
            	
            	<?php
            			if(elgg_is_logged_in()){
            				//TODO estrare il nonme tramite API fwd_offerte
            				$logged_user_id=elgg_get_logged_in_user_guid();
            				
            				echo "<li>Ciao utente ".$logged_user_id." </li>";
            			}
            		            	 
				?>

                <li><a href=""><i class="glyphicon glyphicon-heart"></i></a></li>
                <li><a href=""><i class="glyphicon glyphicon-shopping-cart"></i> </a></li>
                <li><a href=""><i class="glyphicon glyphicon-user"></i></a></li>
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