<?php


namespace Foowd;

/**
 * Per convenzione, tutti i dati vengono passati e ritornati in formato json
 *
 * Naturalmente il check dei valori sara' fatto ancor prima di arrivare a chiamare questo servizio
 */

class API{

	// /**
	//  * faccio partire curl per la chiamata al servizio
	//  * @return [type] [description]
	//  */

	public static function Request(string $url, string $method , array $params){
		
		if(is_callable('curl_init')){
			// inizializzo la chiamata
			//$url="http://localhost/api_offerte/public_html/api/offers";
			$url = elgg_get_plugin_setting('api', \Foowd\Param::pid() ) . $url;
			$ch = curl_init($url);
		}else{
			register_error(elgg_echo("Impossibile eseguire l'azione"));
			// qui eventualmente generare il log per avvisare che curl non funziona
		   	return false;
		}
		
		// converto tutti i dati in un array da passare in formato json via curl
		foreach($params as $field => $value){
			
			// modifico automaticamente le virgole in punti, 
			// in modo da passare il corretto formato per salvataggio mysql.
			if(is_numeric($value)) $value = preg_replace('@,@', '.', $value);			
			$ar[$field] = $value;
		}


		// se non e' impostato type, allora non vado avanti
		$testPost = (isset($ar['type']) && $method==="POST" );
		$testGet = (preg_match('@type@i', $url) && $method==="GET");
		if(!$testPost && !$testGet){
			register_error(elgg_echo('Error: undefined type'));
			return false;
		}

		// utile per debug tramite POSTMAN
		//register_error(json_encode($url));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    // curl_setopt($ch, CURLOPT_URL, $URL);
	    // curl_setopt($ch, CURLOPT_USERAGENT, $this->_agent);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    // curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->_cookie_file_path);
	    // curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->_cookie_file_path);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ar));
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

	    // utile per debug tramite POSTMAN
	    //register_error(json_encode($ar));

		// dovrebbe ritornare un formato json
		$output=curl_exec($ch);
		
		//$_SESSION['my']=json_encode($url);
		register_error($output);
		
		$returned = json_decode($output);


		// i prezzi li visualizzo con la virgola
		foreach ($returned->body as $key => $value) {
			foreach($value as $field => $var){
				// i valori numerici per convenzione hanno la virgola come separatore decimale
				if(is_numeric($var)){
					$returned->body[$key]->{$field} = preg_replace('@\.@', ',', $var);
				}	
			}
		}

		return $returned;
	}

}