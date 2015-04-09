<?php

namespace Foowd\FApi;
//use \Offer as Offer;
// use Base\OfferQuery as OfferQuery;
// use Base\TagQuery as TagQuery;

/**
 * @apiDefine MyResponse
 *
 * @apiParam (Response) {Bool}				response 	false, in caso di errore
 * @apiParam (Response) {String/json}		[errors] 	json contenente i messaggi di errore
 * @apiParam (Response) {String/json}		[body] 		json contenente i parametri da ritornare in funzione della richiesta
 * @apiParam (Response) {String} 			[msg] 		messaggi ritornati
 * 
 */


class ApiPrefer extends \Foowd\FApi{

	public $needle  = array(
			"create"	=> "OfferId, UserId, Qt", 
			"delete"	=> "OfferId, UserId"
	);



	public function __construct($app, $method = null){

		parent::__construct($app, $method);

	}


	
	/**
	 *
	 * @api {post} /prefer create
	 * @apiName create
	 * @apiGroup Prefer
	 * 
 	 * @apiDescription Crea una nuova offerta, o incrementa/decrementa della quantita' specificata se gia' presente. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	OfferId 	id dell'offerta
	 * @apiParam {Integer}  	PreferId 	id locale dell'utente (non ExternalId)
	 * @apiParam {Integer}  	Qt 			quantita' da istanziare o da incrementare/decrementare; Se positiva incrementa, altrimenti decrementa.
	 * 
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "OfferId":"92",
	 *      "UserId": "5",
	 *      "type":"create",
	 *      "Qt":"-43"
	 *  }
	 *
	 *     
	 */	
	public function create($data){
		
		$prefer = \PreferQuery::Create()
				->filterByOfferId($data->OfferId)
				->filterByUserId($data->UserId)
				->findOne();

		if($prefer){

			$value = $prefer->getQt() + $data->Qt;
			if($value<0){
				$value = 0;
				//$Json['errors']['Qt'] = "Raggiunta la soglia minima dello zero";
			}
			$prefer->setQt( $value );
		
		}else{ 

			//check foreign constraint
			$of = \OfferQuery::Create()->filterById($data->OfferId)->findOne();
			if(!$of) $Json['errors']['OfferId']="L' ID non combiacia con alcuna offerta";

			$us = \UserQuery::Create()->filterById($data->UserId)->findOne();
			if(!$us) $Json['errors']['UserId']="L' ID non combiacia con alcun utente locale";

			if(isset($Json)){
				$Json['response'] = false;
				return $Json;
			}
			

			$prefer = new \prefer();

			if(!isset($data->Created)) $data->Created = date('Y-m-d H:i:s');

			foreach($data as $field => $value) $prefer->{'set'.$field}($value);
		}

		return $this->FSave($prefer);
	}


	/**
	 *
	 * @api {post} /prefer delete
	 * @apiName delete
	 * @apiGroup Prefer
	 * 
 	 * @apiDescription Crea una nuova offerta, o incrementa/decrementa della quantita' specificata se gia' presente. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	OfferId 	id dell'offerta
	 * @apiParam {Integer}  	PreferId 	id locale dell'utente (non ExternalId)
	 * 
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *      "OfferId":"92",
	 *      "UserId": "5",
	 *      "type":"create",
	 *      "Qt":"-43"
	 *  }
	 *
	 *     
	 */	
	protected function delete($data){

		$prefer = \PreferQuery::create()
		  		->filterByUserId($data->UserId)
		  		->filterByOfferId($data->OfferId)
		 		->find();

		$status = false;

		 // in teoria la query dovrebbe restituire un solo valore, ma meglio controllare
		 if( $prefer->count() == 1){
		 	$prefer->delete();
		 	$status = true;
		 }else{
		 	$Json['errors'] = "Si sta tentando di cancellare una preferenza che non esiste.";
		 }

		 $Json['response'] = $status;
		 
		return $Json;	
	}


	/**
	 *
	 * @api {get} /offers search
	 * @apiName search
	 * @apiGroup Prefer
	 * 
 	 * @apiDescription Per ottenere la lista delle offerte di un dato Publisher.
 	 *
 	 * Strutturato in questo modo, cerca solo le intersezioni dei filtri.
	 * 
	 * @apiParam {String} 		type 			metodo da chiamare
	 * @apiParam {Mixed}	  	[qualunque] 	qualunque colonna. Il valore puo' essere una STRINGA o un ARRAY come stringa-JSON con chiavi "max" e/o "min" (lettere minuscole).
	 * @apiParam {String} 		[order] 		stringa per specificare l'ordinamento. Il primo elemento e' la colonna php. Si puo' specificare se 'asc' o 'desc' inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified
	 * @apiParam {Mixed}	  	[offset] 		Il valore puo' essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi "page" e "maxPerPage" per sfruttare la paginazione di propel.
	 *
	 * @apiParamExample {url} URL-Example:
	 * 
	 * http://localhost/api_offerte/public_html/api/prefer?OfferId=38&type=search
	 * 
	 * @apiUse MyResponse
	 * 
	 */
	protected function search($data){
		
		$msg = "Nessun risultato trovato: prova a ripetere la ricerca escludendo qualche opzione.";

		if(isset($data->order)){
			$order = array_map('trim' , explode( ',', $data->order) );
			// imposto asc come default
			if(!isset($order[1])) $order[1]= 'asc';
			//var_dump($order);
			unset($data->order);
		}

		if(isset($data->offset)){
			if(preg_match('@{.+}@',$data->offset)){
				$offset = (array) json_decode($data->offset);
			}else{
				$offset = $data->offset;
			}
			unset($data->offset);
		}

		// NB: se ritorna qualche errore sul Model Criteria e' perche' probabilmente sto' usando un dato di ricerca che non esiste!
		//var_dump($data);

		$obj = \PreferQuery::create();
		foreach($data as $key => $value){
			//echo "$key";
			if(preg_match('@{.+}@',$value)) $value = (array) json_decode($value);
			//var_dump($value);
			$obj = $obj->{'filterBy'.$key}($value);
		}
		
		if(isset($order)) $obj = $obj->{'orderBy'.$order[0]}($order['1']);

		// offset e/o limit
		if(isset($offset)){
			if(is_array($offset)){
				$obj = $obj->paginate($page = $offset['page'], $maxPerPage = $offset['maxPerPage']);

				if($offset['page'] * $offset['maxPerPage'] > $offset['maxPerPage'] + $obj->getFirstIndex()){
					$Json['response']=false;
					$Json['errors']['offset']="Hai superato il limite massimo di offerte visualizzabili con questi filtri";
					return($Json);
				}

			}else{
				$obj = $obj->limit($offset)->find();				
			}
			$prefer = $obj;

		}else{

			$prefer = $obj->find();
		
		}
		
		if(!$prefer->count()){
			 $Json['response'] = false;
		}
		
		$return = array();
		
		foreach ($prefer as $single) {
			$ar = $single->toArray();
			// if(isset($data->Publisher)){
			// 	$ar = \OfferQuery::Create()		
			//       ->filterById($ar['OfferId'])
			//       ->find()->toArray();
			//  }else{
			//  	$ar = \UserQuery::Create()
			//  			->filterById($ar['UserId'])
			//       ->find()->toArray();	
			//  }
			array_push($return, $ar );
		}

		if(!isset($Json['response'])){ $Json['response'] = true;}
		else {$Json['errors'] = $msg; }
		$Json['body'] = $return;
		return $Json;
		
	}


}

