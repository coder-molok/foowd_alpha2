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


class ApiUser extends \Foowd\FApi{

	public $needle = array();

	public function __construct($app, $method = null){

		parent::__construct($app, $method);

	}

	public function create($data){

		$user = new \User();
		
		$user->setExternalId("5");
		$user->setName('lol');
		//

		var_dump($user->validate());

		var_dump($this->FSave($user));
	}

}

