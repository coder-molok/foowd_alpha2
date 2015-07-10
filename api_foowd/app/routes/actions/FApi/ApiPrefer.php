<?php

namespace Foowd\FApi;
//use \Offer as Offer;
// use Base\OfferQuery as OfferQuery;
// use Base\TagQuery as TagQuery;

/**
 * @apiDefine MyResponsePrefer
 *
 * @apiParam (Response) {Bool}				response 		false, in caso di errore
 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
 * @apiParam (Response) {String/json}		[body] 			json contenente i parametri da ritornare in funzione della richiesta
 * @apiParam (Response) {String/json}		[body-offer] 	ciascuna preferenza ritornata contiene il parametro Offer: un JSON con tutti i dati relativi all'offerta a cui si riferisce la preferenza
 * @apiParam (Response) {String} 			[msg] 			messaggi ritornati
 * 
 */


class ApiPrefer extends \Foowd\FApi{

	
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
	 * @apiParam {Integer}  	ExternalId 	id elgg dell'utente
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
	public $needle_create = "OfferId, ExternalId, Qt";
	public function create($data){

		// recupero l'id dell'utente
		$UserId = \UserQuery::Create()->filterByExternalId($data->ExternalId)->findOne();


		if(!$UserId){
			$Json['errors']['ExternalId']="ExternalId not match nothing";
			$Json['response'] = false;
			return $Json;
		}

		$UserId = $UserId->getId();
		unset($data->ExternalId);
		$data->UserId = $UserId;

		
		$prefer = \PreferQuery::Create()
				->filterByOfferId($data->OfferId)
				->filterByUserId($UserId)
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
	 * @apiParam {Integer}  	ExternalId 	id elgg dell'utente
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
	public $needle_delete = "OfferId, ExternalId";
	protected function delete($data){

		// recupero l'id dell'utente
		$UserId = \UserQuery::Create()->filterByExternalId($data->ExternalId)->findOne();


		if(!$UserId){
			$Json['errors']['ExternalId']="ExternalId not match nothing";
			$Json['response'] = false;
			return $Json;
		}

		$UserId = $UserId->getId();
		unset($data->ExternalId);
		$data->UserId = $UserId;

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
	 * @api {get} /prefer search
	 * @apiName search
	 * @apiGroup Prefer
	 * 
 	 * @apiDescription Oltre a svolgere una ricerca nella tabella preferenze, ritorna anche il parametro extra "<strong>Offer</strong>" contenente l'offerta a cui si riferisce, in formato JSON.
 	 *
 	 * Strutturato in questo modo, cerca solo le intersezioni dei filtri.
	 * 
	 * @apiParam {String} 		type 			search
	 * @apiParam {Str/Num}		[ExternalId] 	numero intero o sequenza di interi separati da virgola
	 * @apiParam {Mixed}	  	[qualunque] 	qualunque colonna. Il valore puo' essere una STRINGA o un ARRAY come stringa-JSON con chiavi "max" e/o "min" (lettere minuscole).
	 * @apiParam {String} 		[order] 		stringa per specificare l'ordinamento. Il primo elemento e' la colonna php. Si puo' specificare se 'asc' o 'desc' inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified
	 * @apiParam {Mixed}	  	[offset] 		Il valore puo' essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi "page" e "maxPerPage" per sfruttare la paginazione di propel.
	 *
	 * @apiParamExample {url} URL-Example:
	 * 
	 * http://localhost/api_offerte/public_html/api/prefer?OfferId=38&type=search&ExternalId=37,52
	 * 
	 * @apiUse MyResponsePrefer
	 * 
	 */
	public static function search($data){
		
		$msg = "Nessun risultato trovato: prova a ripetere la ricerca escludendo qualche opzione.";

		if(isset($data->ExternalId)){
			// recupero lo userId e poi elimino $data->ExternalId
			$extid = $data->ExternalId;
			unset($data->ExternalId);
			if(is_string($extid) && preg_match('@,@', $extid)) $extid = explode(',', $extid);
			$UserId = \UserQuery::Create()->filterByExternalId($extid)->find();
			// se l'utente con quell'id esterno esiste, allora lo utilizzo, altrimenti blocco tutto
			if(is_object($UserId)){
				$data->UserId = array();
				foreach ($UserId as $single) {
					array_push($data->UserId, $single->getId());
					}	
				
			}else{
				$Json['response'] = false;
				$Json['errors']['Foreign'] = "L'id passato non e' associato a nessun utente API";
				$Json['errors']['File'] = __FILE__. ' Line: '.__LINE__;
				echo json_encode($Json);
				exit(7);
			}
		}

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
			if(is_string($value) && preg_match('@{.+}@',$value)) $value = (array) json_decode($value);
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
		
		// if(!$prefer->count()){
		// 	 $Json['response'] = false;
		// }
		
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
			// $uid = $ar['UserId'];
			// $ar['UserId'] = self::IdToExt($uid);

			$OfferId = $ar['OfferId'];
			unset($ar['OfferId']);

			$ar['Offer'] = \OfferQuery::Create()->filterById($OfferId)->findOne()->toArray();
			$ar['Offer']['Publisher'] = self::IdToExt($ar['Offer']['Publisher']);
			$ar['Offer']['totalQt'] = 0;
			$pf = \PreferQuery::Create()->filterByOfferId($OfferId)->find();

			foreach($pf as $sing){
				// var_dump($sing);
				$sing = $sing->toArray();
				$ar['Offer']['totalQt'] += $sing['Qt'];
			}

			array_push($return, $ar );

		}

		if(!isset($Json['response'])){ $Json['response'] = true;}
		else {$Json['errors'] = $msg; }
		$Json['body'] = $return;
		return $Json;
		
	}


}

