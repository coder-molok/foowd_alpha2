<?php
// probabilmente questa dovrebbe essere pubblica...
gatekeeper();

$api = new \Foowd\API();
if($api){

	$data['publisher'] = elgg_get_logged_in_user_guid();
	$api->Read('offer', $data);
	$r = $api->stop();
	//var_dump($r);
	
	// se sono qui la validazione lato elgg e' andata bene
	// ma ora controllo quella lato API remote
	$str = '';
	if($r->response){
		foreach($r->body as $key ){
			$str.= 'Titolo: '.$key->name. ' Tags: '.$key->tags."\n\r<br/>";
			$str.= '    '.$key->description. "\n\r<br/>";
			//$str.= '    '.$key->id. "\n\r<br/>";
			$str.= elgg_view('output/url', array(
					// associate to the action
					'href' => elgg_get_site_url() . "action/foowd_offerte/delete?id=" . $key->id,
				    'text' => elgg_echo('elimina: '.$key->id),
				    'is_action' => true,
				    'is_trusted' => true,
				    //'confirm' => elgg_echo('deleteconfirm'),
				    'class' => 'elgg-button elgg-button-delete',
			    ));//."\n\r<br/><br/><br/>";
			$str.= elgg_view('output/url', array(
					// associate to the action
					'href' => elgg_get_site_url() . "foowd_offerte/single?id=" . $key->id,
				    'text' => elgg_echo('modifica: '.$key->id),
				    //'is_action' => true,
				    //'is_trusted' => true,
				    //'confirm' => elgg_echo('deleteconfirm'),
				    'class' => 'elgg-button elgg-button-delete',
			    ))."\n\r<br/><br/><br/>";
		}
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

$str.= elgg_view('output/url', array(
		// associate to the action
		'href' => elgg_get_site_url() . "foowd_offerte/add",
	    'text' => elgg_echo('Crea'),
	    'class' => 'elgg-button elgg-button-delete',
    ))."\n\r<br/><br/><br/>";

//$response = json_decode(file_get_contents('http://localhost/api_offerte/public_html/offers'),true);

//var_dump( $response);

//error_log($response);

// add the form to this section
$content .= elgg_view('custom/offersList',array('offersList' => $response));


// optionally, add the content for the sidebar
$sidebar = "side bar";

// layout the page
$body = elgg_view_layout('one_sidebar', array(
   'content' => $content.$str,
   'sidebar' => $sidebar
));
// draw the page
echo elgg_view_page($title, $body);