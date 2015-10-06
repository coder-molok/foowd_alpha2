<?php

namespace Foowd\FApi;
//use \Offer as Offer;
// use Base\OfferQuery as OfferQuery;
// use Base\TagQuery as TagQuery;

/**
 * @apiDefine MyResponseUser
 *
 * @apiParam (Response) {Bool}				response 	false, in caso di errore
 * @apiParam (Response) {String/json}		[errors] 	json contenente i messaggi di errore
 * 
 */


class ApiUser extends \Foowd\FApi{


	public function __construct($app, $method = null){

		parent::__construct($app, $method);

	}

	
	/**
	 *
	 * @api {post} /user create
	 * @apiName create
	 * @apiGroup User
	 * 
 	 * @apiDescription Crea un nuovo utente. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {String} 		Name 		nome dell'utente
	 * @apiParam {Integer}  	ExternalId 	id Elgg
	 * @apiParam {Enum}  		Genre 		{standard, offerente}: tipologia utente
	 * @apiParam {String} 		[Location] 	luogo
	 * 
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *   "type":"create",
	 *   "Name":"gigi",
	 *   "Genre":"standard",
	 *   "Location": "torino",
	 *   "ExternalId":"54"
	 *  }
	 *
	 *     
	 */	
	public $needle_create = "Name, Genre, ExternalId";
	public function create($data){

		// unset($data->type);
		// unset($data->method);

		$user = \UserQuery::Create()
				->filterByExternalId($data->ExternalId)
				->findOne();

		if($user) return array('errors'=>'non puoi creare un utente gia\' presente', 'response' => false);
		
		// modifico prima del salvataggio
		if(isset($data->Image)) $data->Image = base64_decode($data->Image);
		
		$user = new \User();
		foreach($data as $field => $value) $user->{'set'.$field}($value);
		
		//var_dump($user->validate());

		return $this->FSave($user);
	}



	/**
	 *
	 * @api {post} /user update
	 * @apiName update
	 * @apiGroup User
	 * 
 	 * @apiDescription Aggiorno dati utente: tutti tranne l'ExternalId.
	 * 
	 * @apiParam {String} 		type 			metodo da chiamare: update
	 * @apiParam {Integer}  	ExternalId 		id Elgg
	 * @apiParam {Enum}  		Genre 			{standard, offerente}: tipologia utente
	 * @apiParam {String} 		[Location] 		luogo
	 * @apiParam {String} 		[Description]	Descrizione dell'utente
	 * @apiParam {String} 		[Image] 		Immagine. Deve essere uno stream base64_encode, in particolare ottenibile mediante "base64_encode(stream_get_contents(fopen("immagine.jpg","rb")))"
	 * 
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *   "type":"update",
	 *   "Name":"gigi",
	 *   "Genre":"standard",
	 *   "Location": "torino",
	 *   "ExternalId":"54",
	 *   "Description":"Sono superbellissimo",
	 *   "Image":"stringa base64_encode....."
	 *  }
	 *
	 *     
	 */	
	public $needle_update = "ExternalId";
	public function update($data){

		// unset($data->type);
		// unset($data->method);

		$user = \UserQuery::Create()
				->filterByExternalId($data->ExternalId)
				->findOne();

		if(!$user) return array('errors'=>'Utente non presente nel DB API', 'response' => false);
		
		$fields = $data;
		unset($fields->ExternalId);
		unset($fields->type);
		if(isset($fields->Image)) $fields->Image = base64_decode($fields->Image);
		
		foreach($data as $field => $value) $user->{'set'.$field}($value);
		
		//var_dump($user->validate());

		return $this->FSave($user);
	}


	/**
	 *
	 * @api {post} /user delete
	 * @apiName delete
	 * @apiGroup User
	 * 
 	 * @apiDescription Elimina utente.
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	ExternalId 	id Elgg
	 * 
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *   "type":"delete",
	 *   "ExternalId":"54"
	 *  }
	 *
	 *     
	 */	
	public $needle_delete = "ExternalId";
	protected function delete($data){

		$user = \UserQuery::create()
		  		->filterByExternalId($data->ExternalId)
		 		->find();

		$status = false;

		 // in teoria la query dovrebbe restituire un solo valore, ma meglio controllare
		 if( $user->count() == 1){
		 	$user->delete();
		 	$status = true;
		 }else{
		 	$Json['errors'] = "Si sta tentando di cancellare un utente che non esiste.";
		 }

		 $Json['response'] = $status;
		 
		return $Json;	
	}


	/**
	 *
	 * @api {post} /user search
	 * @apiName search
	 * @apiGroup User
	 * 
 	 * @apiDescription Restituisce "response false" se l'ExternalId passato non corrisponde ad alcun utente,
 	 * 					altrimenti ritorna true e il Genere dell'utente
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	ExternalId 	id Elgg
	 * @apiParam {String}  		return 		elenco dei campi da ritornare (PHP name di Propel), separati dalla virgola
	 * 
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *  {
	 *   "type":"search",
	 *   "ExternalId":"54",
	 *   "return":"Description,Image"
	 *  }
	 *
	 *
 	 * @apiParam (Response) {String}		[Image] 		l'immagine salvata nel DB. Questo stream viene ritornato come base64_encode.
 	 * 
	 *     
	 */	
	// per Blob data: http://propelorm.org/Propel/cookbook/working-with-advanced-column-types.html#getting-blob-values
	public $needle_search = "ExternalId";
	protected function search($data){
		$user = \UserQuery::Create()
				->filterByExternalId($data->ExternalId)
				->findOne();

		if(!$user) return array('errors'=>'utente inesistente.', 'response' => false);

		$user = $user->toArray();
		// var_dump($user);
		
		$return['body']	= $user;

		// $default = array("Genre");

		// if(isset($data->return)){
		// 	$r = $data->return;
		// 	$tmp = explode(',', $data->return);
		// 	$default = array_merge($default, $tmp);
		// }

		// foreach ($default as $field) {
		// 	$return[$field] = $user->{'get'.$field}();
		// }

		// if(isset($return['Image']) && $return['Image'] !== null){
		// 	$return['Image'] = base64_encode(stream_get_contents($return['Image'], -1, 0));
		// 	//$return['meta'] = stream_get_meta_data($img);
		// }

		$return['response']= true;

		return $return;
		
	}


		/**
		 *
		 * @api {get} /user commonOffers
		 * @apiName commonOffers
		 * @apiGroup User
		 * 
	 	 * @apiDescription Crea un nuovo utente. 
		 * 
		 * @apiParam {String} 		type 		metodo da chiamare (commonOffers)
		 * @apiParam {Integer}  	ExternalId 	gruppo di Id dei quali si vogliono trovare le offerte comuni e non
		 * 
	 	 * 
		 * @apiParamExample {json} Request-Example:
		 *  {
		 *   "type":"create",
		 *   "ExternalId":"54,63"
		 *  }
		 *
		 *
		 * 
		 * @apiParam (Response) {Bool}				response 		false, in caso di errore
	 	 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
	 	 * @apiParam (Response) {String/json}		body 			json contenente i parametri da ritornare in funzione della richiesta. Il parametro offers contiene le preferenze con aggiunto il parametro "friends" che e' l'array con gli id matchanti. I dati relativi agli Id (
		 * @apiParam (Response) {array}				[body-offers]  	array aggiunto a ciascuna offerta e contenente gli id matchanti con l'elenco ExternalId 			
		 *     
		 */	
		public $needle_commonOffers = "ExternalId";
		public function commonOffers($data){

			$r['response'] = true;

			// apidId
			$apiUsers = array_map( function($u){return $this->ExtToId($u); } , explode(',', $data->ExternalId) );
			$elggUsers = explode(',', $data->ExternalId);

			$offers = array();


			$o = \OfferQuery::Create()
					->usePreferQuery()
						->filterByUserId($apiUsers)
					->endUse()
				->find();

			// var_dump($o);
			foreach($o as $of){
				$oId = $of->getId();
				
				if(array_key_exists($oId, $offers)) continue;

				$ar = $of->toArray();

				// ora aggiungo un array contenente solo la lista degli utenti che matchano con le preferenze
				// NB: la lista e' gia' con ExternalId (elggId)
				foreach($of->getPrefers() as $p){
					$ar['friends'][] = $this->IdToExt( $p->getUserId() );
				}
				// ritorno solo quelli che matchano
				$ar['friends'] = array_intersect($ar['friends'], $elggUsers);
				$offers[$oId] = $ar;
			}

			// NB: la chiave associativa di $offers mi serviva solo per evitare di ripetere l'operazione su eventuali offerte duplicate restituite dalla query
			$r['body']['offers'] = array_values($offers);
			
			return $r;
		}


}

