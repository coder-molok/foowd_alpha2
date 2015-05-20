<?php
// make sure only logged in users can see this page
gatekeeper();

$Pid = \Uoowd\Param::pid();

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$title = "Aggiungi la tua Offerta";

// start building the main column of the page
$content = elgg_view_title($title);

//var_dump($_SESSION['sticky_forms']['foowd_offerte/add']['apiError']);

$f = new \Foowd\Action\FormAdd();

// var_dump($_SESSION['sticky_forms']);
// elgg_make_sticky_form($Pid.'/add');
var_dump(elgg_get_sticky_values($Pid.'/add'));
$vars = $f->prepare_form_vars($Pid.'/add');
// var_dump($vars);
//var_dump(elgg_get_logged_in_user_guid());
// $vars['titleError'] = date('Y-m-d H:i:s');

$content .= elgg_view_form($Pid.'/add', array(), $vars);


// add the form stored in /views/default/forms/foowd_offerte/add.php
//$content .= elgg_view_form('foowd_offerte/add');

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page one_sidebar
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content
));

// draw the page
echo elgg_view_page($title, $body);


//var_dump($_SESSION['my']);
