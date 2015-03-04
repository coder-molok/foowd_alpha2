<?php
// make sure only logged in users can see this page
gatekeeper();

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$title = "Offerte Disponibili";

// start building the main column of the page
$content = elgg_view_title($title);

$response = json_decode(file_get_contents('http://localhost/foowd_project/foowd_alpha2/api_offerte/public_html/offers'));

var_dump( $response);


// add the form to this section
$content .= $response;

// optionally, add the content for the sidebar
$sidebar = "";

// layout the page
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content,
   'sidebar' => $sidebar
));

// draw the page
echo elgg_view_page($title, $body);