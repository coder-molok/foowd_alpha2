<?php

// see https://github.com/markharding/elgg-web-services-deprecated/blob/master/lib/user.php

//  Using $jsonexport to produce json output has been deprecated

// piccolo tutorial
// see https://www.marcus-povey.co.uk/2009/08/25/using-elggs-rest-like-api/

// $debug = false;

function foowd_find_match_first($baseImgs, $match){
    $fileMatch = false;
    $it = new RecursiveDirectoryIterator($baseImgs, RecursiveDirectoryIterator::SKIP_DOTS); 
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        // compatibilita' con windows
        $name = str_replace(DIRECTORY_SEPARATOR, '/',  $file->getPathname());
        if ($file->isFile() && preg_match($match, $name) ){
            // \Fprint::r($file->getPathname());
            $fileMatch = $file->getPathname();
            break;
        }
    }

    return $fileMatch;

}


elgg_ws_expose_function("foowd.user.friendsOf",
		"foowd_user_friendsOf",
		 array(
			"guid" => array(
				'type' => 'string',
				'required' => true,
				'description' => 'Guid dell`utente per sui si vuole lista amici',
				)),
		 'Dato un id utente ritorno la lista dei sui amici',
		 'GET',
		 false,
		 false
		);

function foowd_user_friendsOf($guid){
	return \Uoowd\APIFoowd::foowdUserFriendsOf($guid);
}



/**
 * chiamata per ottenere immagini via get
 */

elgg_ws_expose_function("foowd.picture.get",
		"foowd_picture_get",
		array(
			"userId" => array(
				'type' => 'string',
				'required' => false,
				'description' => 'Id Elgg dell\'utente',
				),
			"size" => array(
				'type' => 'string',
				'required' => true,
				'description' => 'small - medium - big',
				),
			"offerId" => array(
				'type' => 'string',
				'required' => false,
				'description' => 'Name of the person to be greeted by the API',
				),
			"type" => array(
				'type' => 'string',
				'required' => false,
				'description' => 'Se offerId non necessario, altrimenti avatar - profile',
				),
            "sub1" => array(
                'type' => 'string',
                'required' => false,
                'description' => 'Sottodirectory dopo type',
                ),
		),
		'Passando un\'opportuna combinazione di fields visualizza l\'immagine corrispondente',
		'GET',
		false,
		false
		);

/**
 * gruppi:
 *     offerId , size
 *     userId , type , size 
 *     userId , type , sub1 , size 
 */
function foowd_picture_get(){
	// if($debug) \Uoowd\Logger::addError(__FUNCTION__);
	extract($_GET);
	
    $j = array();
	$j['response'] = false;
	
    $file = false;

	$baseImgs = \Uoowd\Param::imgStore();

	if(isset($offerId)){
        // method=foowd.picture.get&offerId=54&size=medium
		$match = "@offers/$offerId/$size@";
        $j['match'] = $match;
        $file = foowd_find_match_first($baseImgs, $match);
		
	}elseif(isset($userId)){

        $match = 'User-' .$userId. '/' . $type;
        $j['match'] = $match;
        $baseDir = $baseImgs . $match ;
        $exists = file_exists($baseDir);

        if($exists && $type === 'avatar'){
            // method=foowd.picture.get&userId=79&size=medium&type=avatar
            $match = "@$match/$size@";
            $j['match'] = $match;
            $file = foowd_find_match_first($baseDir, $match);
        }elseif($exists && $type === 'profile'){
            // method=foowd.picture.get&userId=97&size=medium&type=profile&sub1=file1
            $match = "@$match/$sub1/$size@";
            $j['match'] = $match;
            $file = foowd_find_match_first($baseDir, $match);
        }

    }


	if(file_exists($file)){
		/** Impostando lo header : se la voglio utilizzare come sorgente*/
		$info = getimagesize($file);
		
        $j['response'] = true;

        header("Content-type: " . $info['mime']);
        echo file_get_contents($file);
        // esco per evitare che vengano scritte altre stringhe, come ad esempio il json di ritorno
        exit(0);

    }else{
        // echo 'file inesistente';
        $j['msg'] = 'File inesistente';
        // NB implementare eventualmente un'immagine di default
        return $j;
    }   
    

}



elgg_ws_expose_function("foowd.admin.purchaseSolve",
		"foowd_purchaseSolve",
		array(
			"PurchaseId" => array(
				'type' => 'string',
				'required' => false,
				'description' => 'Id delle purchases, separati da virgola',
				)
		),
		'Solo per gli amministratori: per ogni PurchaseId prova a chiudere la rispettiva purchase.',
		'POST',
		false,
		false
		);

function foowd_purchaseSolve(){
	// $j['response'] = true;
	$purchases = $_POST['PurchaseId'];

	if(!elgg_is_admin_logged_in()) $j['msg'] = 'Solo gli amministratori possono sfruttare questa chiamata.';
	
	// eseguo la purchase
	$data = array(
		'type'=>'solve',
		'PurchaseId'=>$purchases
	);
	// $j[] = $data;

	$j['api'] = \Uoowd\API::Request('purchase', 'POST', $data);


	return $j;
}



elgg_ws_expose_function("foowd.mixed.territory",
		"foowd_mixed_territory",
		 array(	 ),
		 'Oggetto Json coi parametri per il recupero di dati istat sul territorio',
		 'GET',
		 false,
		 false
		);
function foowd_mixed_territory(){
	// rimuovo il metodo stesso dalla query
	unset($_GET['method']);
	// $_GET['type'] = 'territory';

	$appendUrl = implode('&',array_map_assoc(function($k,$v){return "$k=$v";},$_GET));
	$r = \Uoowd\API::httpCall(APIURL.'territory?'.$appendUrl, 'GET');
	// \Uoowd\Logger::addError($r);
	return $r;
}




require('foowdAPI.php');