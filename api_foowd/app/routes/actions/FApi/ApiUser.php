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
 	 * @apiDescription Crea una nuovo utente. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {String} 		Name 		nome offerta, ovvero il titolo
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
		
		$user = new \User();
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
	 * @apiParam {String} 		Name 		nome offerta, ovvero il titolo
	 * @apiParam {Integer}  	ExternalId 	id Elgg
	 * @apiParam {Enum}  		Genre 		{standard, offerente}: tipologia utente
	 * @apiParam {String} 		[Location] 	luogo
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

}

