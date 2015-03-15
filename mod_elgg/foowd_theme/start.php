<?php


//****************************************************
// blocco di test per la creazione di un utente dal sito Elgg.
// In quanto sperimentale, a breve verra' rimosso.
//
// visto che uso curl, dovrei farci un check durante l'attivazione
// http://hayageek.com/php-curl-post-get/
// altro metodo: https://gist.github.com/twslankard/989974

if(is_callable('curl_init')){

// creare la nuova offerta e poi immagazzinare il suo id
// $ar['publisher'] inteso come l'id del proprietario

$ar['publisher']=get_loggedin_userid();
$ar['name']="cassa di mana potion";
$ar['description']="Questo e' un prodotto da veri nerd...";
$ar['tags']="fantasy, adventure";
$ar['price']='25,57';




$url="http://localhost/api_offerte/public_html/api/v1/offers";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, "call=offer&body=".json_encode($ar));                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
 

 
$output=curl_exec($ch);
echo $output;

curl_close($ch);

//echo $output;
   echo "Enabled";
}
else
{
   echo "Not enabled";
}

//****************************************************



elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {
    
    // deprecato. Uso un page handler
    //elgg_register_plugin_hook_handler('index', 'system', 'new_index');
    
    // Replace the default index page
	elgg_register_page_handler('', 'new_index');
}

function new_index() {
    if (!include_once(dirname(__FILE__) . "/index.php"))
        return false;

    return true;
}
