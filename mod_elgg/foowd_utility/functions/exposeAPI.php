<?php

// see https://github.com/markharding/elgg-web-services-deprecated/blob/master/lib/user.php

//  Using $jsonexport to produce json output has been deprecated
//  

function foowd_find_match_first($baseImgs, $match){
    $file = false;
    $it = new RecursiveDirectoryIterator($baseImgs, RecursiveDirectoryIterator::SKIP_DOTS); 
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        $name = $file->getPathname();
        if ($file->isFile() && preg_match($match, $name) ){
            // \Fprint::r($file->getPathname());
            $file = $file->getPathname();
            break;
        }
    }

    // \Fprint::r($file);

    return $file;

}


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
		'Dato un id utente ritorno la lista dei sui amici',
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

	/** Impostando lo header : se la voglio utilizzare come sorgente*/
	$info = getimagesize($file);

	if(file_exists($file)){
		
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