<?php


namespace Uoowd;

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

	public static function Request($url, $method , $params= array() ){
		// \Uoowd\Logger::addError('FoowdAPI Request');
		// CURL check
		if(is_callable('curl_init')){
			// inizializzo la chiamata
			//$url="http://localhost/api_offerte/public_html/api/offers";
			$url = elgg_get_plugin_setting('api', \Uoowd\Param::uid()  ) . $url;
			$ch = curl_init($url);
			\Uoowd\Logger::addDebug('Url API: ' . $url);
		}else{
			register_error(elgg_echo("Impossibile eseguire l'azione"));
			\Uoowd\Logger::addError('Errore CURL not installed');
		   	return false;
		}
		
		// converto tutti i dati in un array da passare in formato json via curl
		$numeric = array('Price', 'Minqt','Maxqt');
		if(!is_array($params)){
			$params = array();
			\Uoowd\Logger::addError("Attenzione, errore nel passaggio di $params. Dati $url , $method");
		} 
		foreach($params as $field => $value){
			// elimino gli spazi inutili
			$value = trim($value);
			// se e' vuoto, evito di mandarlo
			// if(empty($value)) continue;
			// modifico automaticamente le virgole in punti, 
			// in modo da passare il corretto formato per salvataggio mysql.
			// if(in_array($field, $numeric)) $value = preg_replace('@,@', '.', $value);			
			$ar[$field] = $value;
		}


		// se non e' impostato type, allora non vado avanti
		$testPost = (isset($ar['type']) && $method==="POST" );
		$testGet = (preg_match('@type@i', $url) && $method==="GET");
		if(!$testPost && !$testGet){
			register_error(elgg_echo('Errore: tipo non definito'));
			\Uoowd\Logger::addError('Errore: tipo non definito');
			return false;
		}

		// set Headers
		$now = (new \DateTime(null, new \DateTimeZone("UTC")))->format('U');
		$headers = array('Content-Type: application/json', 'F-Time:'.$now);
		// se il metodo e' post, allora implemento un piccolo controllo
		if($testPost || true){
			array_push($headers, 'F-Check:'.hash_hmac('sha256', $now, 'KFOOWD'));
		}
		
		// utile per debug tramite POSTMAN
		//register_error(json_encode($url));
		\Uoowd\Logger::addDebug('Dati post inviati: '.json_encode($ar));


		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    // curl_setopt($ch, CURLOPT_URL, $URL);
	    // curl_setopt($ch, CURLOPT_USERAGENT, $this->_agent);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    // curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->_cookie_file_path);
	    // curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->_cookie_file_path);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($ch, CURLOPT_VERBOSE, False); // se TRUE con questa scrive nell'error log un output come: A line starting with '>' means "header data" sent by curl, '<' means "header data" received by curl that is hidden in normal cases, and a line starting with '*' means additional info provided by curl.
	    $fp = fopen(dirname(__FILE__).'/curl_request_errorlog.txt', 'w');
	    curl_setopt($ch, CURLOPT_STDERR, $fp); 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ar));
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

	    // utile per debug tramite POSTMAN
	    //register_error(json_encode($ar));

		// dovrebbe ritornare un formato json
		$output=curl_exec($ch);
		
		//$_SESSION['my']=json_encode($url);
		//register_error($output);
		\Uoowd\Logger::addInfo('Responso API: '.$output);
		
		$returned = json_decode($output);

		// i prezzi li visualizzo con la virgola
		if(isset($returned->body)){
			$body = json_decode(json_encode($returned->body), true);
			foreach ($body as $key => $value) {
				if(!is_array($value) && !is_object($value) ) continue;
				foreach($value as $field => $var){
					// i valori numerici per convenzione hanno la virgola come separatore decimale
					if(in_array($field, $numeric)){
						// $returned->body[$key]->{$field} = preg_replace('@\.@', ',', $var);
					}	
				}
			}
		}

		return $returned;
	}


	/**
	 * Nota importante sulle chiamate al WebService:
	 *
	 * Con curl_http (estensione di php) le chiamate vengono svolte dal server e non dal browser,
	 * e questo implica dei punti importanti da sottolineare:
	 *
	 * (-) 	essendo dal server, le chiamate non comportano richieste dati dal device dell'utente(cell, browser, etc)
	 * 
	 * (-) 	essendo svolta dal server, le chiamate agli API SERVICE di ELGG  sono "in incognito", nel senso che il server per elgg non e' l'utente loggato,
	 * 		di conseguenza a meno di non creare dei tokens o degl oAuth, le funzioni elgg_get_logged_in_user_entity() non vedranno mai un utente loggato!!!
	 * 	 	Proprio per questo motivo  se ho bisogno di farmi riconoscere come utente, devo utilizzare javascript, dato che e' nel device (browser, smartphone, etc)
	 * 	 	che sono presenti i cookies, localstorage ed altri metodi di verifica automatica della sessione!!!
	 */


	public static function httpCall($url, $method , $params= array() ){
			// \Uoowd\Logger::addError('FoowdAPI httpCall');
			// CURL check
			if(is_callable('curl_init')){
				$ch = curl_init($url);
				\Uoowd\Logger::addDebug('Url API: ' . $url);
			}else{
				register_error(elgg_echo("Impossibile eseguire l'azione"));
				// qui eventualmente generare il log per avvisare che curl non funziona
			   	return false;
			}
			

			// set Headers
			$now = (new \DateTime(null, new \DateTimeZone("UTC")))->format('U');
			$headers = array(/*'Content-Type: application/json',*/ 'F-Time:'.$now);
			// se il metodo e' post, allora implemento un piccolo controllo
			if($testPost || true){
				array_push($headers, 'F-Check:'.hash_hmac('sha256', $now, 'KFOOWD'));
			}
			
			// utile per debug tramite POSTMAN
			//register_error(json_encode($url));
			\Uoowd\Logger::addDebug('Dati post inviati: '.json_encode($params));


			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    // curl_setopt($ch, CURLOPT_URL, $URL);
		    // curl_setopt($ch, CURLOPT_USERAGENT, $this->_agent);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		    // curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->_cookie_file_path);
		    // curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->_cookie_file_path);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		    curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
		    $fp = fopen(dirname(__FILE__).'/curl_httpCall_errorlog.txt', 'w');
		    curl_setopt($ch, CURLOPT_STDERR, $fp); 
		    // curl_setopt($ch, CURLOPT_STDERR, 'hanler al file in cui salvare output'); // $fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		    // utile per debug tramite POSTMAN
		    //register_error(json_encode($ar));

			// dovrebbe ritornare un formato json
			$output=curl_exec($ch);
			
			//$_SESSION['my']=json_encode($url);
			//register_error($output);
			// \Uoowd\Logger::addInfo('Responso API: '.$output);


			$returned = json_decode($output);
			if(json_last_error() == JSON_ERROR_NONE){
				return $returned;
			}
			else{
				$j['response'] = false;
				$j['msg'] = $output;
				return $j;
			}
		}


	public static function pathPics($id){
		$url = elgg_get_site_url() . 'foowd_utility/image-path';
		return self::httpCall($url,'POST', array('ExternalId'=> $id));
	}


}