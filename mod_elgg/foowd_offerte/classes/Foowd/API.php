<?php


namespace Foowd;

/**
 * Per convenzione, tutti i dati vengono passati e ritornati in formato json
 *
 * Naturalmente il check dei valori sara' fatto ancor prima di arrivare a chiamare questo servizio
 */

class API{

	/**
	 * faccio partire curl per la chiamata al servizio
	 * @return [type] [description]
	 */
	public function __construct(){

		// http://hayageek.com/php-curl-post-get/
		// altro metodo: https://gist.github.com/twslankard/989974
		
		if(is_callable('curl_init')){
			// inizializzo la chiamata
			//$url="http://localhost/api_offerte/public_html/api/v1/offers";
			$url = get_config('ApiDom');
			$this->ch = curl_init($url);
			return true;
		
		}else{

			register_error(elgg_echo("Impossibile eseguire l'azione"));
			// qui eventualmente generare il log per avvisare che curl non funziona
		   	return false;
		}
	}


	/**
	 * chiudo la connessione curl
	 * @return [type] [description]
	 */
	public function stop(){
		curl_close($this->ch);
		return json_decode($this->return);
		//return $this->return;
	}


	/**
	 * Funzione per creare gli oggetti in remoto.
	 * In particolare verra' utilizzato il metodo PUT.
	 * 
	 * @param [type] $route  non la reale route del path, ma un parametro per chiamare l'adeguato metodo nel sito delle API
	 * @param [type] $params coppie chiavi - valori da mandare al sito API per la creazione 
	 */
	public function Create(string $route, array $params){
	
		foreach($params as $field => $value){
			$ar[$field] = $value;
		}

		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, "call=".$route."&body=".json_encode($ar));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		// dovrebbe ritornare un formato json
		$output=curl_exec($this->ch);
		$this->return = $output;
	}

	public function Read(string $route, array $params){
	
		foreach($params as $field => $value){
			$ar[$field] = $value;
		}

		$query = "call=".$route."&body=".json_encode($ar);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		// dovrebbe ritornare un formato json
		$output=curl_exec($this->ch);
		//var_dump($otuput);
		$this->return = $output;
	}

	public function Delete(string $route, array $params){
	
		foreach($params as $field => $value){
			$ar[$field] = $value;
		}

		$query = "call=".$route."&body=".json_encode($ar);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		// dovrebbe ritornare un formato json
		$output=curl_exec($this->ch);
		//var_dump($otuput);
		$this->return = $output;
	}

	public function Update(string $route, array $params){
	
		foreach($params as $field => $value){
			$ar[$field] = $value;
		}
		$ar['call'] = $route;

		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    // curl_setopt($this->ch, CURLOPT_URL, $URL);
	    // curl_setopt($this->ch, CURLOPT_USERAGENT, $this->_agent);
	    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
	    // curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->_cookie_file_path);
	    // curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->_cookie_file_path);
	    curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt($this->ch, CURLOPT_VERBOSE, TRUE);
	    curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($ar));
	    curl_setopt($this->ch, CURLOPT_POST, 1);

		// dovrebbe ritornare un formato json
		$output=curl_exec($this->ch);
		//$_SESSION['myy']=$output;
		$this->return = $output;
	}

}