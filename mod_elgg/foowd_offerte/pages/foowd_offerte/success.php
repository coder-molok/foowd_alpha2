<?php
// make sure only logged in users can see this page
gatekeeper();

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$title = "Offerta Pubblicata";

// start building the main column of the page
$content = elgg_view_title($title);


//$content .= elgg_view('foowd_offerte/add', array(), $vars);
$content .= 'complimenti, hai pullicato la tua offerta con successo.';
$content .= elgg_view('output/url', array(
				'href' => elgg_get_site_url() . \Foowd\Param::pid()."/all",
			    'text' => elgg_echo('All'),
			    'class' => 'elgg-button elgg-button-delete',
		    ))."\n\r<br/><br/><br/>";

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
