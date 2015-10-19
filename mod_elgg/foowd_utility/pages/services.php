<?php

header('Content-Type: application/json; charset=utf-8');



// le relationship sono associate al verb
// controllo se due utenti, dati i loro username, sono amici
if(isset($_POST['verb'])){
	// echo 'relationship';
	// echo json_encode($j);
	$guid1 = get_user_by_username( $_POST['subject'] )->guid;
	$relationship = $_POST['verb'];
	$guid2 = get_user_by_username( $_POST['target'] )->guid;
	// oggetto relationship
	$rel = check_entity_relationship($guid1, $relationship, $guid2);
	// \Fprint::r($rel);
	$j['relationship'] = $rel ? true : false;
	$j['subject'] = $guid1;
	$j['target'] = $guid2;
	echo json_encode($j);
}


// ottengo la lista di amici di un utente, data la sua id
if(isset($_POST['friendsList'])){

	$j['str'] = '';

	$relationship = get_entity_relationships($_POST['guid']);
	foreach($relationship as $rel) $j['str'] .= 'Utente Owner id ' .$rel->guid_one . ' in relazione "' . $rel->relationship .'" con oggetto id ' .$rel->guid_two . '<br/>';

	error_log('here I am');
	$guid = $_POST['guid'];
	$j['response'] = true;
	$j['test'] = 'stringa di ritorno';
	echo json_encode($j);
}
