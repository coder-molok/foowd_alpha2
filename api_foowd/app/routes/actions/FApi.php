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

			case "post": // se il metodo e' post, allora i parametri vengono passati come body
				$data = json_decode($app->request()->getBody());//std class		
				break;

			case "get": // il metodo get acquisisce i parametri via url.
				$data = (object) array_map('trim', $app->request()->Params());
				break;

			default : 
				echo  json_encode(array('msg'=>get_class($this).': richiesta non specificata o errata ', 'response'=>false));
				return;
			
		}

		// controllo i dati, ad esempio per la validazione
		if($e = $this->parseData($data)){
			$Json['errors'] = $e;
			$Json['response'] = false;
			echo json_encode($Json);
			return;
		}



		// $app->getLog()->error('data inside object');
		// $app->getLog()->error($data);

		// i parametri nulli vengono ignorati mediante la loro eliminazione
		foreach($data as $key => $value){
			if(is_null($value) || $value==''){
				// echo  json_encode(array('errors'=>" '$key' e' un parametro vuoto.", 'response'=>false));
			 	// unset($data->{$key});
			 	// return;
			}
			// per quanto setto jquery ajax cache: false
			if($key == "_") unset($data->{$key});
		}

		// salvo i dati nella sessione: questi vengono riutilizzati negli error handling e ritornati nella risposta
		$_SESSION['foowd']['data'] = $data;

		if(isset($data->type)){
			
			// controllo che siano inseriti i dati obbligatori, altrimenti ritorno l'errore
			if(is_array( $verify = $this->checkNeedle($data) )){
				echo  json_encode(array('errors'=>$verify, 'response'=>false));
			}else{
				// evito di portarmi dietro dati inutili
				$type = $data->type;
				unset($data->type);
				if(method_exists($this, $type)){
					$ret = $this->{$type}($data);
					echo json_encode($this->parseResult($ret) );
				}else{
					echo json_encode(array('msg'=>get_class($this).": metodo - $type - inesistente", 'response'=>false));
				}
			}
		}else{
			echo  json_encode(array('msg'=>get_class($this).': metodo non specificato', 'response'=>false));
		}
	}


	/**
	 * Esegue la validazione, dopo aver eventualmente controllato gli hook hookFSave.
	 * @param [type] $obj [description]
	 */
	public function FSave($obj){

		// ciascuna classe figlio puo' sviluppare un proprio hook da svolgere prima del salvataggio.
		if(method_exists($this, 'hookFSave')) call_user_func(array($this, 'hookFSave'), $obj);

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
			$r['body']= $obj->toArray();
		   	$r['response'] = true;
		}

		return $r;

	}

	/**
	 * esegue la validazione, e in caso non vada a buon fine catcha un'eccesione.
	 * @param [type] $obj [description]
	 * @return [obj] se tutto va a buon fine ritorna l'oggetto passato
	 */
	public function Fvalidate($obj){

		// ciascuna classe figlio puo' sviluppare un proprio hook da svolgere prima del salvataggio.
		// if(method_exists($this, 'hookFvalidate')) call_user_func(array($this, 'hookFSave'), $obj);

		//return $obg->validate();
		if (!$obj->validate()) {
		    foreach ($obj->getValidationFailures() as $failure) {
		        // $r['errors'][$failure->getPropertyPath()] = $failure->getMessage();
		        // salvo nella sessione in modo che vengano recuperati nella sessione
		        $j = array(
		        	"column" 	=> $failure->getPropertyPath(),
		        	"msg"		=> $failure->getMessage(),
		        	"groups" 	=> $failure->getConstraint()->groups,
		        	"invalidvalue"	=> $failure->getInvalidValue()
		        );

		        
		        $_SESSION['foowd']['errors']['validation'][] = $j;
		        
		    }
		    throw new \Exception('errore di validazione dei dati');
		}

		return $obj;
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

	public function parseData($data){
		foreach($data as $key => $value){
			//var_dump($key);
			if($key === "Minqt" && !preg_match('/^\d{1,5}(\.\d{0,3})?$/', $value)) $e['Minqt']="Errore di validazione: puoi inserire al massimo 5 cifre intere e 3 decimali";
			if($key === "Maxqt" && !preg_match('/^\d{1,5}(\.\d{0,3})?$/', $value)) $e['Maxqt']="Errore di validazione: puoi inserire al massimo 5 cifre intere e 3 decimali";
			if($key === "Price" && !preg_match('/^\d{1,8}(\.\d{0,2})?$/', $value)) $e['Price']="Errore di validazione: puoi inserire al massimo 8 cifre intere e 2 decimali";
		}

		if(isset($e)) return $e;
		return null;
	}

	/**
	 * Per svolgere operazioni di default sugli oggetti ritornati, ovvero le risposte delle api.
	 * 
	 * @param  [type] $obj API ritornata, in formato array (non ancora json);
	 * @return [type]      l'oggetto passato, al netto delle operazioni di parser.
	 */
	public function parseResult($obj){

		if(!isset($obj['body'])) return $obj;

		foreach($obj['body'] as $key => $single){
			// Converto le date ZULU in orario locale
			// solo per quanto riguarda la visualizzazione
			//date_default_timezone_set("Europe/Rome");
			if(isset($single['Modified']))	$obj['body'][$key]['Modified'] =  date("Y-m-d H:i:s", strtotime($single['Modified']) );
			if(isset($single['Created']))	$obj['body'][$key]['Created'] =  date("Y-m-d H:i:s", strtotime($single['Created']) );
			if(isset($single['Expiration'])) $obj['body'][$key]['Expiration'] =  date("Y-m-d H:i:s", strtotime($single['Expiration']) );
		}
		return $obj;
	}



	/**
	 * gli passo l'id locale di un utente e restituisce il suo externalId
	 * @param [type] $userId [description]
	 */
	// protected function IdToExt($userId){

	// 	$elggId = \UserQuery::Create()->filterById($userId)->findOne();
	// 	// eventualmente implementare un log: ci sarebbero articoli con utente indefinito...
	// 	if($elggId)	return $elggId->getExternalId();
			
	// }

	protected static function IdToExt($userId){

		$elggId = \UserQuery::Create()->filterById($userId)->findOne();
		// eventualmente implementare un log: ci sarebbero articoli con utente indefinito...
		if($elggId)	return $elggId->getExternalId();
			
	}

	
	protected static function ExtToId($elggId){
		// echo 'lol*'.$elggId;


		$userId = \UserQuery::Create()->filterByExternalId($elggId)->findOne();
		// var_dump($userId);
		// eventualmente implementare un log: ci sarebbero articoli con utente indefinito...
		if($userId)	return $userId->getId();
			
	}	




}