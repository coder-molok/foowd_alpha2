<?php
// probabilmente questa dovrebbe essere pubblica...
gatekeeper();

$appendUrl ="type=search&Publisher=".elgg_get_logged_in_user_guid();
$r = \Uoowd\API::Request('offer?'.$appendUrl, 'GET');

$Pid = \Uoowd\Param::pid(); //plugin id

$str = '';
if($r->response){
	$afterTitle = ", <br/>ecco le offerte che hai pubblicato";
	foreach($r->body as $key ){
		$str .= elgg_view('offers/allSingle',array('single' => (array)$key,'pid'=>$Pid ,'guid'=>elgg_get_logged_in_user_guid()) );
	}
}else{
	$afterTitle =", <br/>devi ancora pubblicare la tua prima offerta.";
}

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$user = get_user_entity_as_row(elgg_get_logged_in_user_guid());
$title = $user->name.$afterTitle.'<br/><br/>';

// my debug
//var_dump($_SESSION['my']);


// start building the main column of the page
$content = elgg_view_title($title);

//$content .= get_config('limit').'test';

$str.= elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url() . $Pid ."/add",
	    'text' => elgg_echo('Crea'),
	    'class' => 'elgg-button elgg-button-delete',
    ))."\n\r<br/><br/><br/>";


// add the form to this section
$content .= elgg_view('custom/offersList',array('offersList' => $response));


// optionally, add the content for the sidebar
$sidebar = "";

// layout the page
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content.$str,
   'sidebar' => $sidebar
));
// draw the page
echo elgg_view_page($title, $body);