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
		if(isset($data->GroupConstraint)){
			$constraint = $data->GroupConstraint;
			unset($data->GroupConstraint);
		}


		$user = new \User();

		// se non specifico la data, devo crearla di default
		date_default_timezone_set('Europe/Rome');
		if(!isset($data->Created)) $user->setCreated(date('Y-m-d H:i:s'));

		foreach($data as $field => $value) $user->{'set'.$field}($value);
		//var_dump($user->validate());

		$sv = $this->FSave($user);
		if(!$sv['response']) return $sv; 

		// hook alla tabella dei constraint
		if(isset($constraint)){
			unset($data->GroupConstraint);
			$localId = self::ExtToId($data->ExternalId);
			// $this->app->getLog()->error('user creato  '. $localId);
			$j = [
				'PublisherId' => $localId,
				'GroupConstraint' => json_encode($constraint)
			];

			// creo la nuova corrispondenza
			$g = new \OfferGroupMany();
			foreach($j as $field => $value) $g->{'set'.$field}($value);
			$g->save();
		}


		return ['response'=>true];

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
		$externalId = $fields->ExternalId;
		unset($fields->ExternalId);
		unset($fields->type);
		if(isset($fields->Image)) $fields->Image = base64_decode($fields->Image);

		// hook alla tabella dei constraint
		if(isset($fields->GroupConstraint)){
			$constraint = $fields->GroupConstraint;
			unset($fields->GroupConstraint);
			$localId = self::ExtToId($externalId);
			$j = [
				'PublisherId' => $localId,
				'GroupConstraint' => json_encode($constraint)
			];

			$groups = \OfferGroupManyQuery::create()->filterByPublisherId($localId)->filterByGroupOfferId(NULL)->find();

			// se non ce ne sono, vuol dire che e' il primo, e quindi lo salvo
			$check = true;
			if($groups->count() == 0){
				$g = new \OfferGroupMany();
			}elseif($groups->count() == 1){
				$g = $groups[0];	
			}else{
				$check=false;
				$j['ExternalId'] = $externalId;
				$this->app->getLog()->error('Errore nel salvataggio: per ogni utente publisher dovrebbe esistere un solo parametro NULL per groupofferid, associato al concetto di "tutte le offerte"');
				$this->app->getLog()->error($j);
			}
			
			if($check){
				foreach($j as $field => $value) $g->{'set'.$field}($value);
				$g->save();
			}
		}
		
		// salvo i dati relativi alla tabella utenti
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

		$localId = $user->getId();
		$group = \OfferGroupManyQuery::create()->filterByPublisherId($localId)->filterByGroupOfferId(NULL)->findOne();

		$user = $user->toArray();
		
		if($group) $user['GroupConstraint'] = (array) json_decode($group->getGroupConstraint());

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
	 	 * @apiDescription Trova le offerte comuni a un gruppo di utenti. 
		 * 
		 * @apiParam {String} 		type 			metodo da chiamare (commonOffers)
		 * @apiParam {Integer}  	ExternalId 		gruppo di Id (separati da virgola) dei quali si vogliono trovare le offerte comuni e non
		 * @apiParam {String}    	prefersState 	Filtro preferenze per stato, o lista di stati separati da virgola. Puo' anche essere usato "editable" per sottintendere "newest, pending"
		 * 
	 	 * 
		 * @apiParamExample {json} Request-Example:
		 *  {
		 *   "type":"create",
		 *   "ExternalId":"54,63",
		 *   "prefersState":"editable"
		 *  }
		 *
		 *
		 * 
		 * @apiParam (Response) {Bool}				response 		false, in caso di errore
	 	 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
	 	 * @apiParam (Response) {String/json}		body 			json contenente i parametri da ritornare in funzione della richiesta. Il parametro offers contiene la proprieta' "friends": array contenente le preferenze matchanti la lista di ExternalId
		 * @apiParam (Response) {array}				[body-offers]  	array aggiunto a ciascuna offerta e contenente le preferenze degli id matchanti con l'elenco ExternalId      
		 * 
		 */	
		public $needle_commonOffers = "ExternalId";
		public function commonOffers($data){

			$r['response'] = true;

			// apidId
			$apiUsers = array_map( function($u){return $this->ExtToId($u); } , explode(',', $data->ExternalId) );
			$elggUsers = explode(',', $data->ExternalId);

			if(isset($data->prefersState)){
				$s = $data->prefersState;
				if($s === 'editable'){
					$prefState = array('pending', 'newest');
				}else{
					$prefState = explode(',', $s );
				}
			}

			$offers = array();


			$o = \OfferQuery::Create()
					->usePreferQuery()
						->filterByUserId($apiUsers)
					->endUse()
				->find();

			// var_dump($o);
			foreach($o as $of){
				$oId = $of->getId();
				$friends = array();

				if(array_key_exists($oId, $offers)) continue;

				$ar = $of->toArray();
				// il publisher deve essere ritornato come id di Elgg
				$ar['Publisher'] = $this->IdToExt( $ar['Publisher'] );

				// ora aggiungo un array contenente solo la lista degli utenti che matchano con le preferenze
				// NB: la lista e' gia' con ExternalId (elggId)
				$ar['friends'] = array();
				foreach($of->getPrefers() as $p){
					// $friends[] = /*$this->IdToExt(*/ $p->getUserId() /*)*/;
					$pid = $p->getId();
					$puid = $p->getUserId();
					if( ! in_array($puid, $apiUsers ) ) continue;
					if(isset($prefState) && !in_array($p->getState(),$prefState)) continue;
					$p = $p->toArray();
					$p['UserId'] = $this->IdToExt($p['UserId']);
					$ar['friends'][] = $p;
				}

				// nel caso non vi siano preferenze, cosa che data la query iniziale non dovrebbe avvenire
				if(count($ar) <=0 ) continue;

				$offers[$oId] = $ar;
			}

			// NB: la chiave associativa di $offers mi serviva solo per evitare di ripetere l'operazione su eventuali offerte duplicate restituite dalla query
			$r['body']['offers'] = array_values($offers);
			
			return $r;
		}


}

