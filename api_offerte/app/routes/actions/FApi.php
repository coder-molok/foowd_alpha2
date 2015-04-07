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
		$data->method = $method; 

		if(isset($data->type)){
			// controllo che siano inseriti i dati obbligatori, altrimenti ritorno l'errore
			if(is_array( $verify = $this->checkNeedle($data) )){
				echo  json_encode(array('errors'=>$verify, 'response'=>false));
			}else{
				$this->{$data->type}($data);
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

		if(array_key_exists($obj->type, $this->needle)){	// se il metodo ha dei parametri obbligatori (praticamente tutti)
			//echo 'esist';
			$need = array_map('trim', explode( ',' , $this->needle[$obj->type])  );
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


}