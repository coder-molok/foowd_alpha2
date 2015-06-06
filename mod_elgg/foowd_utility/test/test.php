<?php

elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback'));

function my_get_entity_callback($row)

{

    $user = get_entity($row->guid);

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