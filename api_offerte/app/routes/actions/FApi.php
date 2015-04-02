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
				$data = (object) $app->request()->Params();
				break;
		}

		// ai dati aggiungo il dipo di richiesta
		$data->method = $method; 

		if(isset($data->type)){
			$this->{$data->type}($data);
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
		   $r['response'] = true;
		}

		return $r;

	}


}