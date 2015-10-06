<?php

header('Content-Type: application/json; charset=utf-8');



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

