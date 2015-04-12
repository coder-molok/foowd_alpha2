<?php


//namespace Foowd;

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

	public static function Request($url, $method , array $params = array("type" => null)){
		
		if(is_callable('curl_init')){
			// inizializzo la chiamata
			//$url="http://localhost/api_offerte/public_html/api/offers";
			//$url = elgg_get_plugin_setting('api', \Foowd\Param::pid() ) . $url;
			$url = preg_replace('@ @','',$url);
			//var_dump($url);
			$ch = curl_init($url);
		}else{
			// var_dump("Impossibile eseguire l'azione");
			// qui eventualmente generare il log per avvisare che curl non funziona
			$r['obj']['errors']['curl_init'] = "curl non e' impsostato sul server";
			$r['obj']['response'] = false;
		   	return $r;
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
			var_dump('Error: undefined type');
			return ;
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
		$allData['output']=$output;
		$allData['post']=$ar;
		$allData['get']=$url;


		//var_dump($output);
		
		$returned = json_decode($output);


		// i prezzi li visualizzo con la virgola
		if(isset($returned->body)){
			foreach ($returned->body as $key => $value) {
				foreach($value as $field => $var){
					// i valori numerici per convenzione hanno la virgola come separatore decimale
					if(is_numeric($var)){
						$returned->body[$key]->{$field} = preg_replace('@\.@', ',', $var);
					}	
				}
			}
		}

		$allData['obj'] = $returned;

		return $allData;
		//return $returned;
	}

	public static function Write($url, $method , array $params = array("type" => null)){
		$r = self::Request($url, $method , $params);
			echo '<div class="single-sent">';
			if($method === 'GET') var_dump($r['get']);
			if($method === 'POST') echo self::pretty_json(json_encode($r['post']));
			echo '</div>';

			//var_dump($r['obj']);

			if($r['obj']->response){
				$cls = 'true';
			}else{
				$cls = 'false';
			}
			echo '<div class="single-return-'.$cls.'">';
			echo self::pretty_json($r['output']);
			echo '</div>';
	}

	public static function pretty_json($json) {
 
    $result      = '<pre>';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {
 
        // Grab the next character in the string.
        $char = substr($json, $i, 1);
 
        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;
 
        // If this character is the end of an element, 
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }
 
        // Add the character to the result string.
        $result .= $char;
 
        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }
 
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }
 
        $prevChar = $char;
    }
 
    return $result."</pre>";
}

}