<?php

ob_start();
// var_dump($_SESSION['sticky_forms']['foowd-avatar']);
unset($_SESSION['sticky_forms']['foowd-avatar']);

$vars['guid']=elgg_get_logged_in_user_guid();


echo elgg_view_form('foowd-avatar', array('enctype'=>'multipart/form-data'), $vars);

$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-avatar">'.$body.'</div>';

echo elgg_view_page('Avatar',$body);