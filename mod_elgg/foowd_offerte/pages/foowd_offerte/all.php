<?php
// probabilmente questa dovrebbe essere pubblica...
gatekeeper();

$appendUrl ="type=search&Publisher=".elgg_get_logged_in_user_guid();
$r = \Uoowd\API::Request('offer?'.$appendUrl, 'GET');

$Pid = \Uoowd\Param::pid(); //plugin id

$str = '';
if($r->response){
	foreach($r->body as $key ){
		$str.= 'Titolo: '.$key->Name. ' Tags: '.$key->Tag."\n\r<br/>";
		$str.= 'Contenuto: '.$key->Description. "\n\r<br/>";
		$str.= 'Prezzo: '.$key->Price. "\n\r<br/>";
		$str.= 'li: '.$key->Created. "\n\r<br/>";
		$str.= 'Modified: '.$key->Modified. "\n\r<br/>";
		$str.= elgg_view('output/url', array(
				// associate to the action
				'href' => elgg_get_site_url() . "action/".$Pid."/delete?Id=" . $key->Id,
			    'text' => elgg_echo('elimina: '.$key->Id),
			    'is_action' => true,
			    'is_trusted' => true,
			    'confirm' => elgg_echo('Sei sicuro di voler eliminare questa offerta: '.$key->Id),
			    'class' => 'elgg-button elgg-button-delete',
		    ));//."\n\r<br/><br/><br/>";
		$str.= elgg_view('output/url', array(
				// associate to the action
				'href' => elgg_get_site_url() . $Pid ."/single?Id=" . $key->Id,
			    'text' => elgg_echo('modifica: '.$key->Id),
			    //'is_action' => true,
			    //'is_trusted' => true,
			    //'confirm' => elgg_echo('deleteconfirm'),
			    'class' => 'elgg-button elgg-button-delete',
		    ))."\n\r<br/><br/><br/>";
	//var_dump($key);
	}
}

// my debug
//var_dump($_SESSION['my']);

// set the title
// for distributed plugins, be sure to use elgg_echo() for internationalization
$user = get_user_entity_as_row(elgg_get_logged_in_user_guid());
$title = $user->name.", <br/>ecco le offerte che hai pubblicato";

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