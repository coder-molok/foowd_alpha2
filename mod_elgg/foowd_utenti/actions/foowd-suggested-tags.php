<?php

admin_gatekeeper();

$json = array();


$data = json_decode($_POST['send']);
$action = $_POST['action'];

// if($action == 'save'){

// }


// provvedo all'eliminazione dei tags
if($action == 'delete'){

	$s = new \Foowd\SuggestedTags();
	// elimino la chiave
	$destroy = array();
	foreach($data as $key => $val){
		// echo   $val->tag." - ";
		$destroy[] = $val->tag;
	}
	
	$c = new stdClass();
	$c->key = $destroy;
	$s->delete($c);
	\Uoowd\Logger::addError($destroy);
	$j['msg'] = 'distrutti con successo i tags: ' . implode(', ', $destroy);
}


echo json_encode($json);


