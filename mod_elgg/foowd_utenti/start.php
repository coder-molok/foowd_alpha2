<?php

// classe di default
elgg_register_classes(elgg_get_plugins_path().'foowd_utility/classes');

\Uoowd\Param::checkFoowdPlugins();

// carico i namespace composer di questo plugin
// require_once(elgg_get_plugins_path().\Uoowd\Param::pid().'/vendor/autoload.php');


elgg_register_event_handler('init', 'system', 'utenti_init');

function utenti_init(){

    elgg_register_action("foowd-avatar", elgg_get_plugins_path() . 'foowd_utenti/actions/foowd-avatar.php');
    elgg_register_action("foowd-gallery", elgg_get_plugins_path() . 'foowd_utenti/actions/foowd-gallery.php');
    elgg_register_action("foowd-dati", elgg_get_plugins_path() . 'foowd_utenti/actions/foowd-dati.php');
    elgg_register_action("foowd-order-manager", elgg_get_plugins_path() . 'foowd_utenti/actions/foowd-order-manager.php');

    //Triggered after user registers. Return false to delete the user.
	$user = new \Foowd\User();
    $user->form = 'register';
    elgg_register_plugin_hook_handler('register', 'user', array($user, 'register'));

    // wrap new user creation settings it's default lang
    elgg_register_event_handler('create','user', "set_def_lang");

    // se volessi rimuovere l'hook
    // elgg_unregister_plugin_hook_handler('register', 'user', array('\Foowd\User', 'register'));

    //register a new page handler: solo di prova.
    elgg_register_page_handler('foowd_utenti', 'foowd_utenti_handler');

    

    // forgot password
    // elgg_register_plugin_hook_handler('action', 'user/requestnewpassword', 'pwd_smarrita', 99999999999999999999999999);
    // 'forward, system'
    // elgg_register_plugin_hook_handler('get_sql', 'access', 'smarrita', 99999999);

    // modifico la registrazione lato admin: non servira' quasi mai
    // elgg_extend_view('forms/useradd', 'register/extend');

    // NB: eliminare utente e' un'opzione deprecata da elgg 0.9
    // link utile per implementare https://github.com/Elgg/Elgg/blob/master/actions/avatar/remove.php
    // action: user delete
    
    // elgg_register_event_handler('create', 'user', array('\Foowd\User', 'register'));
    // sovrascrivo la registrazione lato elgg
    elgg_register_action("useradd", __DIR__ . "/actions/useradd.php", "admin");

    // elgg_extend_view('page/elements/sidebar', 'extend/sidebar');

    // estensione della sidebar
    elgg_extend_view('forms/login', 'login/extend_social' /*, 450*/);

    // pagina del profilo
    // elgg_view_exists('profile/detai');
    // elgg_extend_view('profile/details', 'extend/profile');
    
    // Carico il mio css di default
    $css =  'mod/'.\Uoowd\Param::pid()."/css/foowd-utenti.css";
    elgg_register_css('foowd-utenti', $css , 509);
    elgg_load_css('foowd-utenti');

    $css =  'mod/'.\Uoowd\Param::pid()."/css/foowd-profile.css";
    elgg_register_css('foowd-profile', $css , 509);


    // dipendenze
    // elgg_define_js('foowd_utenti/user-register', [
        // 'src' => 'mod/foowd_theme/vendor/bootstrap/dist/js/bootstrap.min.js',
        // 'deps' => array('jquery','elgg','handlebars')
    // ]);

    // nel caso l'utente esista in Elgg, ma non sia stato registrato correttamente:
    // ad esempio per l'amministratore che inizializza Elgg
    checkUser();

}



/**
 * solo script di prova
 * @param  [type] $segments [description]
 * @return [type]           [description]
 */
function foowd_utenti_handler($segments){

    // forward(REFERER);
    //var_dump($segments);

     // test per eventuale login con google+
    if($segments[0] === 'auth'){
        // include elgg_get_plugins_path() . 'foowd_utenti/pages/auth.php';
        \Uoowd\Logger::addError($segments[0]);
        new \Foowd\SocialLogin();
        return true;
    }
    if($segments[0] === 'indexauth'){
        define('AUTH',__DIR__.'/vendor/hybridauth/hybridauth/hybridauth/index.php' );
        // include elgg_get_plugins_path() . 'foowd_utenti/pages/indexauth.php';
        \Uoowd\Logger::addError($segments[0]);
        require(AUTH); 
        // questo require in realta' esegue dei redirect, 
        //pertanto il return sarebbe inutile
        \Uoowd\Logger::addError('dopo require auth');
        return true;
    }

    if($segments[0] === 'profilo'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/profilo.php';
        return true;
    }

    if($segments[0] === 'avatar'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/avatar.php';
        return true;
    }

    if($segments[0] === 'dati'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/dati.php';
        return true;
    }

    if($segments[0] === 'gallery'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/gallery.php';
        return true;
    }

    if($segments[0] === 'social'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/social.php';
        return true;
    }

    if($segments[0] === 'success'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/success.php';
        return true;
    }

    if($segments[0] === 'my-preferences'){
        require elgg_get_plugins_path() . 'foowd_utenti/pages/prefers.php';
        return true;
    }

    return false;

}


/**
 *  imposto italiano come lingua di default
 */
function set_def_lang ($event, $object_type, $object) {
    $object->set("language", "it");
    return true;
}

function checkUser(){
    $user = elgg_get_logged_in_user_entity();
    $guid = $user->guid;

    // se e' un utente loggato, allora continuo coi check
    if($user){
        if(!isset($user->Genre) || !$user->apiSetted){

            \Uoowd\Logger::addWarning('Utente '.$guid.' : il genere non e\' impostato oppure non risulta salvato nel DB API');
            
            // anzitutto controllo se esiste
            $data['type']= "search";
            $data['ExternalId'] = $guid;
            $r = \Uoowd\API::Request('user', 'POST', $data);
            
            // se esiste nelle API salvo il suo genere e sono ok
            if( $r->response ){
                // nel caso non sia impostato il genere nelle API
                if(!$r->body->Genre){
                  \Uoowd\Logger::addError('Utente '.$guid.' , e\' registrato ma non ha Genre specificato');
                  return false;
                } 
                // se tutto e' andato a buon fine, allora posso salvare
                $user->Genre = $r->body->Genre;
                $user->apiSetted = true;
                return true;
            }

            
            // se invece non e' registrato nel DB API allora gli imposto un genere di default e 
            // lo salvo in tale DB: in caso di successo salvo anche su elgg il suo Genere, 
            // altrimenti non lo faccio

            // gli amministratori li imposto come offerenti, perche' devono accedere a tutto
            if(isset($user->Genre)){
               $Genre = $user->Genre;
               \Uoowd\Logger::addError("Provo a salvare utente $guid, che non era presente nelle API DB"); 
            }
            else if($user->admin){
                $Genre = 'offerente';
            }else{
                $Genre = 'standard';
            }

            $data['type']= "create";
            $data['ExternalId'] = $guid;
            $data['Name'] = $user->name;
            $data['Email'] = $user->email;
            $data['Genre'] = $Genre;

            $r = \Uoowd\API::Request('user', 'POST', $data);
            
            if(!$r->response){
                \uoowd\Logger::addError('Utente '.$guid.' , risulta registrato, ma non si riesce a salvarlo nel DB API');
                return false;
            }

            $user->Genre = $r->body->Genre;
            $user->apiSetted = true;
            return true;
        }
    }

}

// function pwd_smarrita($hook, $type, $value, $params){
//     error_log('***************************************');
//     error_log(print_r(func_get_args(), true ));
//     // forward('login');
// }


// function smarrita($hook, $type, $value, $params){
//     error_log('*************************************** smarrita **** ');
//     error_log(print_r(func_get_args(), true ));
//     // if(preg_match("/requestnewpassword/i",$params['current_url'])){
//     //     error_log(__FILE__.' : Foowd, reindirizzamento');
//     //     // \Fprint::r('Reindirizzamento post recupero password.');
//          // forward('login');
//     //  }
// }
// 
// function _elgg_friends_page_handler($segments, $handler) {

// }
