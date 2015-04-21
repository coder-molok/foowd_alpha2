<?php

// classe di default
elgg_register_classes(elgg_get_plugins_path().'foowd_utility/classes');


elgg_register_event_handler('init', 'system', 'utenti_init');

function utenti_init(){

    //Triggered after user registers. Return false to delete the user.
	elgg_register_plugin_hook_handler('register', 'user', 'register_wrap');

    //register a new page handler: solo di prova.
    elgg_register_page_handler('foowd_utenti', 'user_list');

    // NB: eliminare utente e' un'opzione deprecata da elgg 0.9
    // link utile per implementare https://github.com/Elgg/Elgg/blob/master/actions/avatar/remove.php
    // action: user delete

}


function register_wrap($hook, $type, $value, $params){

    return \Foowd\User::register($hook, $type, $value, $params);

}


/**
 * solo script di prova
 * @param  [type] $segments [description]
 * @return [type]           [description]
 */
function user_list($segments){


    //     $check = true;

    //     switch($segments[0]){
    //         case 'all':
    //             include elgg_get_plugins_path() . 'foowd_offerte/pages/foowd_offerte/all.php';
    //             break;
    //         default:
    //             $check = false;
    //             break;
    //     }

    //     return $check;
    // }


    var_dump($segments);
    //\Uoowd\Param::logger($segments);
   
     $users = elgg_get_entities(array('type' => 'user', 'limit' => 0));
     var_dump(count($users));
    foreach ($users as $user) {
       
        // var_dump(get_class_methods (  $user ));
        $field = $user->Genre.'  ***  '.$user->name.'<br/>';

        echo $field;
      

    }
    return true;

}



