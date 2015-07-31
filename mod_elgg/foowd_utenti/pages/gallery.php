<?php

ob_start();
// var_dump($_SESSION['sticky_forms']['foowd-avatar']);
unset($_SESSION['sticky_forms']['foowd-gallery']);

$vars['guid']=elgg_get_logged_in_user_guid();


echo elgg_view_form('foowd-gallery', array('enctype'=>'multipart/form-data'), $vars);

$body = ob_get_contents();

ob_end_clean();

echo elgg_view_page('Gallery',$body);