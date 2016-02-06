<?php

// NB: ritorna true se gia' esiste, false altrimenti
// NB2: per i check vedi register_user di elgg


// PER TUTTI
// se il parametro e' impostato a false, allora e' come se non ci fossero errori

// var_dump($_GET);
// \Uoowd\Logger::addError($_GET);
$json = array();
// di default sono false
foreach ($_GET as $key => $value) {
	$json[$key] = false;
}


// form foowd-dati
// if(isset($_GET['foowd-dati'])){
// 	$user = get_entity($_GET['guid']);

// 	if(isset($_GET['Email'])){
// 		// validazione elgg
// 		// uso il not perche' per me false vuol dire che soddisfo la validazione
// 		$json['Email'] = !is_email_address($_GET['Email']);
// 		if(! empty(get_user_by_email( $_GET['Email'] ) ) ) $json['Email']=true;
// 		if($_GET['Email'] === $user->email) $json['Email'] = false;
// 	}

// 	if(isset($_GET['username'])){
// 		$json['username'] = false;
// 		if( is_object(get_user_by_username( $_GET['username'] )) ) $json['username']=true;
// 	}

// 	echo json_encode($json);
// 	return;
// }



// false
if(isset($_GET['username'])){
	$json['username'] = false;
	if( is_object(get_user_by_username( $_GET['username'] )) ) $json['username']=true;
}


// empty
if(isset($_GET['email'])){
	$json['email'] = false;
	if(! empty(get_user_by_email( $_GET['email'] ) ) ) $json['email']=true;
}


echo json_encode($json);

// prendo la lista degli utenti attuali, in modo da fare un check prima 
// elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback', 'name'=>'simoneg'));
// function my_get_entity_callback($row){

//     $user = get_entity($row->guid);

//     \Fprint::r($user->fake);
//     \Fprint::r($user->guid);
//     \Fprint::r($user->name);
//     \Fprint::r($user->username);
//     \Fprint::r($user->email);
//     \Fprint::r($user->Genre);
//     \Fprint::r($user->idAuth);

//     echo '<br>';

// }
