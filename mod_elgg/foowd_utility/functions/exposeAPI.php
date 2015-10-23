<?php

// see https://github.com/markharding/elgg-web-services-deprecated/blob/master/lib/user.php

//  Using $jsonexport to produce json output has been deprecated



elgg_ws_expose_function("foowd.user.friendsOf",
                "foowd_friendsOf",
                 array(
                        "guid" => array(
                                'type' => 'string',
                                'required' => true,
                                'description' => 'Name of the person to be greeted by the API',
                                )),
                 'Dato un id utente ritorno la lista dei sui amici',
                 'POST',
                 false,
                 false
                );

function foowd_friendsOf($guid){
        $j['response'] = false;
        $user = elgg_get_logged_in_user_entity();

        // \Uoowd\Logger::addError($user);

        if(!$user){
                $j['msg'] = 'Questa richiesta puo\' avvenire solo dal sito e mentre sei loggato';
        }else{
                $j['msg'] = "Salve $user->username, hai guid $user->guid e mi chiedi di $guid";
        }

	return $j;
}
