<?php

$user = get_user_by_email('scardoni.simone@gmail.com')[0];
$guid = $user->guid;

// $user->fake = array('lolg'=>1, 'lal'=> 'io');

// var_dump($user->metadata );


// elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback'));

$user = elgg_get_entities_from_metadata(
	// array('metadata_names'=>array('Genre'), 'metadata_values'=>array('standard'))
	// array('metadata_names'=>array('fake'), 'metadata_values'=>array('lol'))
	// array( 'metadata_names'=>array('fake'), 'metadata_values'=>array('i') )
	);

var_dump($user);




elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback'));
function my_get_entity_callback($row)

{

    $user = get_entity($row->guid);

    var_dump($user->fake);
    var_dump($user->guid);
    var_dump($user->name);
    var_dump($user->username);
    var_dump($user->email);
    var_dump($user->Genre);
    var_dump($user->idAuth);

    echo '<br>';

}


// public function cleanAndRename(){
// 	\Uoowd\Logger::addError($this->saveDir);
// 	\Uoowd\Logger::addError(get_input('offerGuid'));
// 	$ite=new \RecursiveDirectoryIterator($this->saveDir);
// 	foreach (new \RecursiveIteratorIterator($ite) as $filename=>$cur) {
// 	    $filesize=$cur->getSize();

// 	    $bytestotal+=$filesize;
// 	    $nbfiles++;
// 	    \Uoowd\Logger::addError("$filename => $filesize\n");
// 	}
// }


exit;