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

	public static function Request(string $route, string $method, array $params){

		if(is_callable('curl_init')){
			// inizializzo la chiamata
			//$url="http://localhost/api_offerte/public_html/api/v1/offers";
			$url = get_config('ApiDom') . $route;
			$ch = curl_init($url);
			//return true;
		}else{
			register_error(elgg_echo("Impossibile eseguire l'azione"));
			// qui eventualmente generare il log per avvisare che curl non funziona
		   	return false;
		}
	
		foreach($params as $field => $value){
			$ar[$field] = $value;
		}
		
		$ar['type'] = $method;

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
	    curl_setopt($ch, CURLOPT_POST, 1);

		// dovrebbe ritornare un formato json
		$output=curl_exec($ch);
		//$_SESSION['my']=json_encode($url);
		return json_decode($output);
	}

}