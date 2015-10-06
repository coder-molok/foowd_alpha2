<?php


/**
 * per "maneggiare" il menu opzionale del singolo utente (es. voce : rimuovi amico) vedere
 * 		elgg_register_plugin_hook_handler("register", "menu:entity", "friend_request_entity_menu_handler");
 * 	del plugin friend_request, che aggiunge un oggetto ElggMenuItem
 */

/**
 *  per sovrascrivere la pagina friend di default:
 		
 		elgg_register_page_handler('friends', 'foowd_friends_handler');

 		e


 		// handler e' la pagina base che sto intercettando
 		function foowd_friends_handler($segments, $handler){

 		    // set page owner guid serve perche' nella pagina richiamata si utilizza proprio questa funzione per ottenere 
 		    // l'entita' (il proprietario)

 		    elgg_set_context('friends');
 		    if (isset($segments[0]) && $user = get_user_by_username($segments[0])) {
 		        elgg_set_page_owner_guid($user->getGUID());
 		    }
 		    // aggiunta da me: se non specifico la pagina dell'utente, allora manda alla pagina amici dell'utente loggato
 		    else{
 		        $user = elgg_get_logged_in_user_entity();
 		        elgg_set_page_owner_guid($user->getGUID());
 		    }
 		    if (!elgg_get_page_owner_guid()) {
 		        return false;
 		    }
 		    switch ($handler) {
 		        case 'friends':
 		            require_once elgg_get_plugins_path() . 'foowd_utenti/pages/friends.php';
 		            break;
 		        default:
 		            return false;
 		    }
 		    return true;

 		}
 */

// \Fprint::r($vars);


// l'amico e' il proprietario della board!
$friend = $vars['entity'];
$query['owner'] = $friend->username;

// io utente, vado a vedere la board del mio amico
// $owner = elgg_get_page_owner_entity();
// $guidFriend = $owner->guid;
$options1 = array(
	'href' => elgg_get_site_url().'board?owner=' .  $query['owner'],
	'text' => 'Visualizza Board',
	'class' => 'friends-extend'
);
echo elgg_view('output/url', $options1 );

echo ' | ';

$options2 = array(
	'href' => elgg_get_site_url(). 'profile/' .  $query['owner'],
	'text' => 'Visualizza Profilo',
	'class' => 'friends-extend'
);
echo elgg_view('output/url', $options2 );

?>

<style>
.friends-extend{
	margin: 10px;
	font-size: 0.9em;
}

a.friends-extend, a:link.friends-extend{
	color: #CF8BCD;
}
</style>