<?php
admin_gatekeeper();
ob_start();

/**
 * Links utili
 *     - http://learn.elgg.org/en/latest/design/database.html , per visualizzare basi elgg su entity, e relationship
 *     - http://learn.elgg.org/en/latest/guides/database.html , tutorial pratico su entita via OOP
 *     
 */


elgg_require_js('foowdJs');

echo "test";
?>
<script>
    requirejs(['foowdJs'], function(fw){
        console.log(JSON.stringify(fw))
    })
</script>
<?php

$body = ob_get_contents();

ob_end_clean();

$body = '<div class="foowd-page-avatar">'.$body.'</div>';

echo elgg_view_page('Avatar',$body);





// $me = elgg_get_logged_in_user_entity();
// echo $me->guid;


// creazione oggetto

// class ElggFoowd extends ElggObject { 

//     public function __construct($row = null){
//         parent::__construct();
//         $this->subtype = 'foowd';
//     }

// }
// $ent = new ElggObject();
// echo $ent->getType();
// echo $ent->subtype = 'foowd';
// echo $ent->getGUID();
// \Fprint::r($ent);
// $ent->save();
// \Fprint::r($ent);
// trovo gli oggetti che hanno sottotipo foowd
// $ent = elgg_get_entities(array('type_subtype_pairs'=>array('object'=>'foowd')) );
// echo '<br>';
// foreach($ent as $o){
//     echo $o->getSubtype().' - ';
//     echo $o->getGuid();
//     $o->delete();
// }


/////////////////////////////
// Send the notification: utile con site_notification, altrimenti si limita a inviare una mail
// a me (array anche),  da 109
// $plugN = 'site_notifications';
// $plug = elgg_get_plugin_from_id($plugN);
// if($plug->isActive()){
//     \Fprint::r('active');
//     // notify_user($me->guid, 109, 'subj', 'notifica di test', array());
// }else{
//     \Fprint::r('plugin non attivo');
// }
////////////////////////////////////


// \Fprint::r( apache_request_headers() );

// file_put_contents(__DIR__ .'/headerstest.txt', json_encode(apache_request_headers()));

// $offer = new \Uoowd\FoowdOffer();
// $offer->solveEdited(); 




return;

// $to = '';
// $m = elgg_send_email('foowd',$to,'random',$html , array('htmlBody'=> $html) );

// get user ritorna un array...
$user = get_user_by_email('scardoni.simone@gmail.com')[0];

$guid = $user->guid;
$data['guid'] = $guid;

$url= elgg_get_site_url() . \Uoowd\Param::page('http')->foowdAPI . 'foowd.user.friendsOf';
$r = \Uoowd\API::httpCall($url, 'POST', $data);

// \Fprint::r($url);
?>
    <script>
    //     var http = new XMLHttpRequest();
    //     var url = "<?php echo $url; ?>";
    //     var data = new FormData();
    //     data.append('guid', <?php echo $guid;?>);
    //     http.open("POST", url, true);

    //     //Send the proper header information along with the request
    //     // http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //     // http.setRequestHeader("Content-length", params.length);
    //     // http.setRequestHeader("Connection", "close");

    //     http.onreadystatechange = function() {//Call a function when the state changes.
    //         if(http.readyState == 4 && http.status == 200) {
    //             alert(http.responseText);
    //         }
    //     }
    //     http.send(data);
    </script>
<?php


// $user->fake = array('lolg'=>1, 'lal'=> 'io');

// \Fprint::r($user->metadata );


// elgg_get_entities(array('types'=>'user','callback'=>'my_get_entity_callback'));

$user = elgg_get_entities_from_metadata(
	// array('metadata_names'=>array('Genre'), 'metadata_values'=>array('standard'))
	// array('metadata_names'=>array('fake'), 'metadata_values'=>array('lol'))
	array( 'metadata_names'=>array('fake'), 'metadata_values'=>array('i') )
	);

//\Fprint::r($user);


$admins =elgg_get_admins();


$users = elgg_get_entities(array('types'=>array('user','admins')) );

$allUsers = array_merge($admins, $users);

foreach ($allUsers as $single) {
    my_get_entity_callback($single);
}



// elgg_get_entities(array('type'=>array('user','admins'),'callback'=>'my_get_entity_callback'));
function my_get_entity_callback($row)

{

    $user = get_entity($row->guid);
    echo '<h1>'.$user->name.'</h1>';

    // \Fprint::r($user);

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
    foreach($relationship as $rel) echo 'Utente Owner id ' .$rel->guid_one . ' in relazione "' . $rel->relationship .'" con oggetto id ' .$rel->guid_two . '<br/>';


    echo '<br>';

}


// molok e' presente in entrambi gli array...
// probabilmente perche' gli utenti registrati direttamente come admin in qualche modo non sono pienamente rintracciabili come users,
// mentre quelli registrati normalmente e poi elevati ad admin probabilmente vengono registrati anche come normali utenti.

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