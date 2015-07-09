<?php

// NB: ritorna true se gia' esiste, false altrimenti

// var_dump($_GET);

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
