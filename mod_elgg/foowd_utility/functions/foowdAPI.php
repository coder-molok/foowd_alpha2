<?php

/**
 * In questa sezione espongo le funzioni adibite a interrogare il DB API
 */

// funzione di supporto
function array_map_assoc( $callback , $array ){
  $r = array();
  foreach ($array as $key=>$value)
    $r[$key] = $callback($key,$value);
  return $r;
}



/**
 * i dati posto sono passati o come POST o come body in formatao JSON
 * @return [type] [description]
 */
function get_post(){
	if(count($_POST) <= 0 ){
	 	$entityBody = stream_get_contents(\Uoowd\Utility::detectRequestBody());
		$data = (array) json_decode($entityBody);
	}else{
		$data = $_POST;
	}
	return $data;
}



// configurazioni globali
define('APIURL', elgg_get_plugin_setting('api', \Uoowd\Param::uid() )   );

unset($_GET['__elgg_uri']);



/**
 * Ricerca tra le offerte: in teoria la ricerca non ha alcun vincolo, pertanto non eseguo alcun controllo
 */
elgg_ws_expose_function("foowd.offer.search",
		"foowd_offer_search",
		 array(
		 	"forCurrentUser" => array(
				'type' => 'bool',
				'required' => false,
				'description' => 'Specifica alle offerte bisogna aggiungere le preferenze dell\'attuale utente',
				),
		 	 	"withFriends" => array(
		 			'type' => 'bool',
		 			'required' => false,
		 			'description' => 'Specifica se deve essere fatto un match con gli amici',
		 		)
		 ),
		 'Lista delle offerte con filtri',
		 'GET',
		 false,
		 false
		);
function foowd_offer_search(){
	// rimuovo il metodo stesso dalla query
	unset($_GET['method']);
	$_GET['type'] = 'search';

	if(elgg_is_logged_in()){
		if(isset($_GET['forCurrentUser'])){ 
			$_GET['ExternalId'] = elgg_get_logged_in_user_guid();
		}
		// se devo, aggiungo gli amici
		if(isset($_GET['withFriends'])){
			if($_GET['withFriends'] == true){
				$ar=  \Uoowd\APIFoowd::foowdUserFriendsOf( elgg_get_logged_in_user_guid() )['friends'];
				$ar[] = elgg_get_logged_in_user_guid();
				$guid = implode($ar, ',');
				$_GET['ExternalId'] = $guid;
			}
		}
	}
	// rimuovo i parametri che non devo passare
	$unset = array('withFriends', 'forCurrentUser');
	array_map(function($v){unset($_GET[$v]);}, $unset);

	$appendUrl = implode('&',array_map_assoc(function($k,$v){return "$k=$v";},$_GET));
	// \Uoowd\Logger::addError($appendUrl);
	$r = \Uoowd\API::offerGet($appendUrl);
	// \Uoowd\Logger::addError($r);
	return $r;
}



/**
 * Aggiungi/Rimuovi preferenza
 */
elgg_ws_expose_function("foowd.prefer.manage",
		"foowd_prefer_manage",
		 array(),
		 'Aggiunta/Rimozione preferenza',
		 'POST',
		 false,
		 false
		);
function foowd_prefer_manage(){
	elgg_gatekeeper();
	$post = get_post();
	$post['ExternalId'] = elgg_get_logged_in_user_guid();
	$r = \Uoowd\API::preferPost($post);
	return $r;
}



/**
 * ricerca sulle preferenze
 */
elgg_ws_expose_function("foowd.prefer.search",
		"foowd_prefer_search",
		 array(
		 	"withFriends" => array(
				'type' => 'bool',
				'required' => false,
				'description' => 'Specifica se deve essere fatto un match con gli amici',
				)
		 ),
		 'Lista delle preferenze con filtri: i classici delle API DB',
		 'GET',
		 false,
		 false
		);
function foowd_prefer_search(){
	// rimuovo il metodo stesso dalla query
	unset($_GET['method']);
	$_GET['type'] = 'search';
	$guid = elgg_get_logged_in_user_guid();
	// se devo, aggiungo gli amici
	if(isset($_GET['withFriends'])){
		if($_GET['withFriends'] == true){
			$ar=  \Uoowd\APIFoowd::foowdUserFriendsOf($guid)['friends'];
			$ar[] = $guid;
			$guid = implode($ar, ',');
		}
		unset($_GET{'withFriends'});
	}

	$_GET['ExternalId'] = $guid;

	$appendUrl = implode('&',array_map_assoc(function($k,$v){return "$k=$v";},$_GET));
	$r = \Uoowd\API::preferGet($appendUrl);
	// \Uoowd\Logger::addError($r);
	return $r;
}
