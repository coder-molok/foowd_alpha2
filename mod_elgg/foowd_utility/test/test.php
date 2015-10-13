<?php

/**
 * Links utili
 *     - http://learn.elgg.org/en/latest/design/database.html , per visualizzare basi elgg su entity, e relationship
 *     - http://learn.elgg.org/en/latest/guides/database.html , tutorial pratico su entita via OOP
 *     
 */


admin_gatekeeper();

$to = 'simoneguerriero84@yahoo.it';
elgg_send_email('foowd',$to,'random','Email di prova.', array());

$user = get_user_by_email('scardoni.simone@gmail.com')[0];
$guid = $user->guid;

// $user->fake = array('lolg'=>1, 'lal'=> 'io');

// \Fprint::r($user->metadata );


// elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback'));

$user = elgg_get_entities_from_metadata(
	// array('metadata_names'=>array('Genre'), 'metadata_values'=>array('standard'))
	// array('metadata_names'=>array('fake'), 'metadata_values'=>array('lol'))
	// array( 'metadata_names'=>array('fake'), 'metadata_values'=>array('i') )
	);

//\Fprint::r($user);




elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback'));
function my_get_entity_callback($row)

{

    $user = get_entity($row->guid);
    echo '<h1>'.$user->name.'</h1>';

    \Fprint::r($user->fake);
    \Fprint::r($user->guid);
    \Fprint::r($user->name);
    \Fprint::r($user->username);
    \Fprint::r($user->email);
    \Fprint::r($user->Genre);
    \Fprint::r($user->idAuth);

    /**
     * SUi metadata:
     *
     * - se uso un metadata custom, ovvero personalizzato, il metadata viene sovrascritto e salvato nel DB di elgg
     * - INVECE se uso un metadata di built in, ovvero uno di quelli gia' presenti nella struttura dell'entita', 
     *     allora per sovrascriverlo DEVO SALVARLO ESPLICITAMENTE!!!
     */
    $metadata = elgg_get_metadata(array(
        'metadata_owner_guid' => $user->guid,
        'limit' => 0,
    ));
    // \Fprint::r($meta);
    foreach($metadata as $meta) echo 'Metadata: ' .$meta->name .' e valore: ' . $meta->value.'<br/>';


    echo '<h3>Relationship</h3>';
    $relationship = get_entity_relationships($user->guid);
    foreach($relationship as $rel) echo 'Utente Owner id ' .$rel->guid_one . ' in relazione "' . $rel->relationship .'" con oggetto id ' .$rel->guid_two . '<br/?>';


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