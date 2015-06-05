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

exit;