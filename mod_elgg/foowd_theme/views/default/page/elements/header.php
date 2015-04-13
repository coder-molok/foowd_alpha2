<?php
/**
 * Header for layouts
 *
 * Elgg Header
 *
 */
$crumbs = explode("/",$_SERVER["REQUEST_URI"]);
if($crumbs[count($crumbs)-1]){
	
echo'
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
                <li><a href=""><i class="glyphicon glyphicon-heart"></i></a></li>
                <li><a href=""><i class="glyphicon glyphicon-shopping-cart"></i> </a></li>
                <li><a href=""><i class="glyphicon glyphicon-user"></i></a></li>
            </ul>
        </div>
    </div>
</nav>';
}