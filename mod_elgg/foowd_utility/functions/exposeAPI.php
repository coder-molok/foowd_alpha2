<?php

// see https://github.com/markharding/elgg-web-services-deprecated/blob/master/lib/user.php
// elgg_ws_expose_function("foowd.users.active",
//                 "count_active_users",
//                  array("minutes" => array('type' => 'int',
//                                           'required' => false),
//                  		'greeting' => array(
//                  		                        'type' => 'string',
//                  		                        'required' => false,
//                  		                        'default' => 'Hello',
//                  		                        'description' => 'Greeting to be used, e.g. "Good day" or "Hi"',
//                  		                    )
//                  ),
//                  'Number of users who have used the site in the past x minutes',
//                  'GET',
//                  false,
//                  false
//                 );

// function count_active_users($minutes=10) {
//     $seconds = 60 * $minutes;
//     $count = count(find_active_users($seconds, 9999));
//     $count = array('count'=>'count', 'mio'=>'random');
//     return $count;
// }



elgg_ws_expose_function("user.friendsOf",
                "ffriendsOf",
                 array("guid" => array('type' => 'int')),
                 'Dato un id utente ritorno la lista dei sui amici',
                 'POST',
                 false,
                 false
                );


function ffriendsOf($guid){
	// error_log($guid);
	// error_log($_GET);

	$j['lol']= 'bella';
	return $j;
}