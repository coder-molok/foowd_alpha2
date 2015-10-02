<?php

// TODO
// inserire controllo utenti gia' iscritti


ob_start();
// var_dump($_SESSION['sticky_forms']['foowd-avatar']);
unset($_SESSION['sticky_forms']['foowd-avatar']);

// $vars['guid']=elgg_get_logged_in_user_guid();

$auth = new \Foowd\HyAuth();

// session_unset();



$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-social">'.$body.'</div>';

echo elgg_view_page('Social',$body);