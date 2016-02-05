<?php
// probabilmente questa dovrebbe essere pubblica...
gatekeeper();

$user = elgg_get_logged_in_user_entity();

if($user->Genre != 'offerente' || !$user->isAdmin()){
	register_error('Siamo spiecenti ma non godi dei permessi per accedere alla pagina cercata.');
	forward(REFERER);
}



$appendUrl ="type=search&Publisher=".elgg_get_logged_in_user_guid();
$r = \Uoowd\API::offerGet($appendUrl);

$Pid = \Uoowd\Param::pid(); //plugin id

$str = '';

if($r->response && !empty($r->body)){
	$afterTitle = ", <br/>ecco le offerte che hai pubblicato";
	// var_dump($r->body);
	foreach($r->body as $key ){
		$key = $key->offer;
		$str .= elgg_view('offers/allSingle',array('single' => (array)$key,'pid'=>$Pid ,'guid'=>elgg_get_logged_in_user_guid()) );
	}
}else{
	$afterTitle =", <br/>devi ancora pubblicare la tua prima offerta.";
}

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$user = get_user_entity_as_row(elgg_get_logged_in_user_guid());
$title = 'foowd-all';


// my debug
//var_dump($_SESSION['my']);


// start building the main column of the page
$content = elgg_view_title($user->username.$afterTitle.'<br/><br/>');

//$content .= get_config('limit').'test';

$str.= elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url() . $Pid ."/add",
	    'text' => elgg_echo('Crea Nuova'),
	    'class' => 'elgg-button',
    ))."\n\r<br/><br/><br/>";


// optionally, add the content for the sidebar
$sidebar = "";

// layout the page
// $body = elgg_view_layout('one_sidebar', array(
//    'content' => $content.$str,
//    'sidebar' => $sidebar
// ));
// draw the page
echo elgg_view_page($title, $content.$str);