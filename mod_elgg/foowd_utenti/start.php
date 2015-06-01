<?php

// classe di default
elgg_register_classes(elgg_get_plugins_path().'foowd_utility/classes');


elgg_register_event_handler('init', 'system', 'utenti_init');

function utenti_init(){

    //Triggered after user registers. Return false to delete the user.
	$user = new \Foowd\User();
    $user->form = 'register';
    elgg_register_plugin_hook_handler('register', 'user', array($user, 'register'));

    // se volessi rimuovere l'hook
    // elgg_unregister_plugin_hook_handler('register', 'user', array('\Foowd\User', 'register'));

    //register a new page handler: solo di prova.
    elgg_register_page_handler('foowd_utenti', 'user_list');

    // modifico la registrazione lato admin: non servira' quasi mai
    elgg_extend_view('forms/useradd', 'register/extend');

    // NB: eliminare utente e' un'opzione deprecata da elgg 0.9
    // link utile per implementare https://github.com/Elgg/Elgg/blob/master/actions/avatar/remove.php
    // action: user delete
    
    // elgg_register_event_handler('create', 'user', array('\Foowd\User', 'register'));
    // sovrascrivo la registrazione lato elgg
    elgg_register_action("useradd", __DIR__ . "/actions/useradd.php", "admin");

    // estensione della sidebar
    elgg_extend_view('page/elements/sidebar', 'extend/sidebar');
}



/**
 * solo script di prova
 * @param  [type] $segments [description]
 * @return [type]           [description]
 */
function user_list($segments){

    
    //var_dump($segments);

     // test per eventuale login con google+
    if($segments[0] === 'auth'){
        include elgg_get_plugins_path() . 'foowd_utenti/pages/auth.php';
        return;
    }
    if($segments[0] === 'indexauth'){
        define('AUTH',__DIR__.'/vendor/hybridauth/hybridauth/hybridauth/index.php' );
        include elgg_get_plugins_path() . 'foowd_utenti/pages/indexauth.php';
        return;
    }

    //return;
    $authPage = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    $config = array(
          "base_url" => $authPage.'auh',
          "providers" => array (
            "Google" => array (
              "enabled" => true,
              "keys"    => array ( "id" => "108856046715-v5vl192ibtbit586p0klsp5oh0pl2elk.apps.googleusercontent.com", "secret" => "G95n2a3_dQHHXMNzgLZfvg71" ),
              "scope"           => "https://www.googleapis.com/auth/userinfo.profile ". // optional
                                   "https://www.googleapis.com/auth/userinfo.email"   , // optional
              // "access_type"     => "offline",   // optional
              // "approval_prompt" => "force",     // optional
              // "hd"              => "domain.com" // optional
        )));

    var_dump($authPage);
    var_dump($_SERVER);

     
        //require_once( "/path/to/hybridauth/Hybrid/Auth.php" );
     
        // $hybridauth = new \Hybrid_Auth( $config );
     
        // $adapter = $hybridauth->authenticate( "Google" );
     
        // $user_profile = $adapter->getUserProfile();

        //var_dump($user_profile);


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


function checkUser(){

    $user = elgg_get_logged_in_user_entity();
    $guid = $user->guid;
    // echo $guid;
    // echo $user->Genre;

    if($user){
        if(!isset($user->Genre)){
            var_dump('not set');

            $user->Genre = 'offerente';
            // $data['type']= "create";
            // $data['ExternalId'] = $guid;

            // $r = \Uoowd\API::Request('user', 'POST', $data);



            exit(0);
        }
    }

}
