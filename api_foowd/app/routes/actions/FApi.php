<?php


namespace Foowd;

abstract class FApi{

	public function __construct($app, $method = null){

		$this->app = $app;

		// in base al parametro type associo una specifica azione.
		// il parametro verra' impostato nei plugin Elgg.
		// Le richieste GET recuperano i dati esclusivamente dall'url
		// Le richieste POST recuperano i dati esclusivamente dal body, e in formato json
		
		switch($method){
			case null: 
				echo  json_encode(array('msg'=>get_class($this).': richiesta non specificata', 'response'=>false));
				return;

			case "post": // se il metodo e' post, allora i parametri vengono passati come body
				$data = json_decode($app->request()->getBody());//std class		
				break;

			case "get": // il metodo get acquisisce i parametri via url.
				$data = (object) array_map('trim', $app->request()->Params());
				break;
		}

		// ai dati aggiungo il dipo di richiesta
		// $data->method = $method; 
		if(isset($this->hookData))	call_user_func(array($this, 'hookData'), array($data,$this->hookData));
		
		// i parametri nulli e' molto meglio toglierli, per evitare incoerenze con la validazione
		foreach($data as $key => $value){
			if(is_null($value) || $value=='') unset($data->{$key});
		}
		//var_dump($data); return;

		if(isset($data->type)){
			
			// controllo che siano inseriti i dati obbligatori, altrimenti ritorno l'errore
			if(is_array( $verify = $this->checkNeedle($data) )){
				echo  json_encode(array('errors'=>$verify, 'response'=>false));
			}else{
				// evito di portarmi dietro dati inutili
				$type = $data->type;
				unset($data->type);
				$ret = $this->{$type}($data);
				//var_dump($ret);
				echo json_encode($this->parse($ret) );
			}
		}else{
			echo  json_encode(array('msg'=>get_class($this).': metodo non specificato', 'response'=>false));
		}
	}

	public function FSave($obj){

		//return $obg->validate();
		if (!$obj->validate()) {
		    foreach ($obj->getValidationFailures() as $failure) {
		        //echo "Property ".$failure->getPropertyPath().": ".$failure->getMessage()."\n";
		        $r['errors'][$failure->getPropertyPath()] = $failure->getMessage();
		    }
		    $r['response'] = false;
		}
		else {
			$obj->save();
		   $r['response'] = true;
		}

		return $r;

	}

	/**
	 * controllo se sono presenti i parametri obbligatori descritti in $needle della classe ereditante
	 * @param  [type] $obj [description]
	 * @return [type]      [description]
	 */
	public function checkNeedle($obj){

		// recupero tutti i metodi publici di questa clase
		$needle = get_class_vars( get_class($this) );  

		if(array_key_exists('needle_'.$obj->type, $needle)){	// se il metodo ha dei parametri obbligatori (praticamente tutti)
			//echo 'exist';
			$need = array_map('trim', explode( ',' , $needle['needle_'.$obj->type])  );
			// var_dump($need);
			// if(count(array_intersect( array_flip((array) $obj), $need)) === count($need)){
			// 	//echo "trovate chiavi obbligatorie";
			// }
			foreach($need as $key){
				if(!array_key_exists($key, (array) $obj)){
					$error['fields'][$key] = "$key - questo campo deve essere specificato";
				}
			}
		}

		if(isset($error) && count($error) > 0) return $error;
		return true;

	}


	/**
	 * Per svolgere operazioni di default sugli oggetti ritornati, ovvero le risposte delle api.
	 * 
	 * @param  [type] $obj API ritornata, in formato array (non ancora json);
	 * @return [type]      l'oggetto passato, al netto delle operazioni di parser.
	 */
	public function parse($obj){

		if(!isset($obj['body'])) return $obj;

		foreach($obj['body'] as $key => $single){
			// Converto le date ZULU in orario locale
			// solo per quanto riguarda la visualizzazione
			//date_default_timezone_set("Europe/Rome");
			if(isset($single['Modified']))	$obj['body'][$key]['Modified'] =  date("Y-m-d H:i:s", strtotime($single['Modified']) );
			if(isset($single['Created']))	$obj['body'][$key]['Created'] =  date("Y-m-d H:i:s", strtotime($single['Created']) );
		}
		return $obj;
	}


	public function hookData($data){
		//var_dump($data);
		foreach($data[1] as $value){
			if($value === 'Publisher' && isset($data[0]->Publisher)){
				 $data[0]->Publisher = \UserQuery::Create()->filterByExternalId($data[0]->Publisher)->findOne();
				 if(is_object($data[0]->Publisher)){
				 	$data[0]->Publisher = $data[0]->Publisher->getId();
				 }else{
				 	$Json['response'] = false;
				 	$Json['errors']['Foreign'] = "local ExternalId e Publisher passato non combaciano";
				 	echo json_encode($Json);
				 	exit(7);
				 }
			}
		}
		//var_dump($data);
	}


}