<?php

gatekeeper();

// PROTOTIPO
// Get input data
// $guid = (int) get_input('guid');

// // Make sure we actually have permission to edit
// $entity = get_entity($guid);
// if ($entity->getSubtype() == "subtype_of_your_choice") {
//     // Delete it!
//     if($entity->delete()) {
//         // Success message
//         system_message(elgg_echo("delete_success"));
//     } else {
//         register_error(elgg_echo("delete_error"));
//     }
// }


$data['Publisher']=elgg_get_logged_in_user_guid();
$data['Id']=(int)get_input('Id');
$data['type']='delete';


$r = \Foowd\API::Request('offers','POST', $data);
//$_SESSION['my'] = $r;

// se sono qui la validazione lato elgg e' andata bene
// ma ora controllo quella lato API remote
if($r->response){
	system_message(elgg_echo("eliminato il post ".$data['Id']));
}else{
	register_error(elgg_echo("errore nell'eliminazione di offerta numero ".$data['Id']));
}


forward(REFERER);