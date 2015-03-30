<?php

// per convenzione tutte le richieste ritornano valori in formato json;
// ciascuno lo stato dell'operazione e' sotto la chiave (o attributo decodificato) response
// type specifica quale metodo richiamare

namespace Foowd\FApi;
//use \Offer as Offer;
// use Base\OfferQuery as OfferQuery;
// use Base\TagQuery as TagQuery;

/**
 * @api {get} /user/:id Request User information
 * @apiName GetUser
 * @apiGroup User
 *
 * @apiParam {Number} id Users unique ID.
 *
 * @apiSuccess {String} firstname Firstname of the User.
 * @apiSuccess {String} lastname  Lastname of the User.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "firstname": "John",
 *       "lastname": "Doe"
 *     }
 *
 * @apiError UserNotFound The id of the User was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "UserNotFound"
 *     }
 */


class ApiOffer extends \Foowd\FApi{


	public function __construct($app, $method = null){

		echo 'lol';

		parent::__construct($app, $method);

		// $this->app = $app;

		// // in base al parametro type associo una specifica azione.
		// // il parametro verra' impostato nei plugin Elgg.
		// // Le richieste GET recuperano i dati esclusivamente dall'url
		// // Le richieste POST recuperano i dati esclusivamente dal body, e in formato json
		
		// switch($method){
		// 	case null: 
		// 		echo  json_encode(array('msg'=>'richiesta non specificata', 'response'=>false));
		// 		return;

		// 	case "post": // se il metodo e' post, allora i parametri vengono passati come body
		// 		$data = json_decode($app->request()->getBody());//std class		
		// 		break;

		// 	case "get": // il metodo get acquisisce i parametri via url.
		// 		$data = (object) $app->request()->Params();
		// 		break;
		// }

		// // ai dati aggiungo il dipo di richiesta
		// $data->method = $method; 

		// if(isset($data->type)){
		// 	$this->{$data->type}($data);
		// }else{
		// 	echo  json_encode(array('msg'=>'metodo non specificato', 'response'=>false));
		// }
	}

	// equivalente a PUT
	public function create($data){

		$offer = new \Offer();
		$this->offerManager($data, $offer);
		
	}

	/**
	 * per aggiornare un' offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function update($data){

		// recupero il post originale
		$offer = \OfferQuery::create()
		  		->filterById($data->Id)
		  		->filterByPublisher($data->Publisher)
		  		->findOne();

		// cancello tutti i tag per riscrivere quelli aggiornati
		\OfferTagQuery::create()
				->filterByOfferId($data->Id)
				->delete();

		$this->offerManager($data, $offer);
	}

	/**
	 * per creare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function offerList($data){

		
		$offer = \OfferQuery::create()
				->filterByPublisher($data->Publisher)
				->find();
		
		$Json = array();
		
		if(!$offer->count()) $Json['errors'] = "Publisher doesn't exists or hasn't offers.";
		
		$return = array();
		
		foreach ($offer as $single) {

			$ar = $single->toArray();
			$tgs = $single->getTags();
			$ar['Tag'] ='';
			foreach ($tgs as $value) {
				foreach(\TagQuery::create()->filterById($value->getId())->find() as $t){
					$ar['Tag'] .= $t->getName().', ';
				}
			}
			array_push($return, $ar);
		}

		$Json['response'] = true;
		$Json['body'] = $return;
		echo json_encode($Json);
		
	}

	/**
	 * per eliminare una nuova offerta.
	 * E' meglio implementare la validazione direttamente tramite propel
	 */
	protected function delete($data){

		$offer = \OfferQuery::create()
		  	->filterById($data->Id)
		  	->filterByPublisher($data->Publisher)
		 	// ->delete();
		 	->find();

		 $status = false;

		 // in teoria la query dovrebbe restituire un solo valore, ma meglio controllare
		 if( $offer->count = 1){
		 	$offer->delete();
		 	$status = true;
		 }
		 
		//echo json_encode($count);
		echo json_encode(array(/*'body'=>$var,*/ 'response' => $status )  );	
	}


	protected function single($data){

		$offer = \OfferQuery::create()
				->filterByPublisher($data->Publisher)
				->filterById($data->Id)
				->find();
		
		
		$return = array();
		
		foreach ($offer as $single) {
			
			// raccolgo tutta la riga della tabella
			$ar = $single->toArray();

			// aggiungo la lista dei tag
			$tgs = $single->getTags();// doppia s!
			$ar['Tag'] ='';
			foreach ($tgs as $value) {
				foreach(TagQuery::create()->filterById($value->getId())->find() as $t){
					$ar['Tag'] .= $t->getName().', ';
				}
			}
			array_push($return,  $ar);
		}

		echo json_encode(array('body'=>$return, 'response'=>true));
	}


	/**
	 * Di fatto creare una nuova offerta o aggiornarne una e' praticamente identico,
	 * pertanto tutte le azioni comuni (validazione e inserimento dei dati), 
	 * possono essere accumunati in questo metodo
	 * 
	 * @param  [type] $data  [description]
	 * @param  [type] $offer [description]
	 * @return [type]        [description]
	 */
	protected function offerManager($data, $offer){
			// rimuovo i parametri superflui dall'array:
			// mi servira' per i cicli successivi
			unset($data->type);
			unset($data->method);

			// memorizzo i singoli errori
			$errors = array();
			$proceed = true;

			// NB: da rivedere la strategia su come aggiungere e rimuovere i tags
			// 
			// prendo i tag inseriti dal form
			$actualTags = explode(',', $data->Tag);
			$actualTags = array_unique($actualTags);	// evito eventuali ripetizioni
			$errors['Tag'] = array();

			// valido i tag (posso anche impostarlo lato propel)
			foreach ($actualTags as $single) {
				// prima di salvare, controllo che il tag sia di una sola parola
				// da vedere: aggiungere controllo sulla presenza di caratteri speciali
				$single = trim($single);
				if(preg_match('@ +@i', $single)){
					array_push($errors['Tag'], "errorone nel tag: ".$single);
					$proceed = false;
				}
			}

			// i settaggi li faccio solamente se tutti i controlli precedenti sono andati a buon fine 
			if($proceed){
				unset($errors['Tag']);	
				
				//$tagManager = OfferTagQuery::create()->find();
				//$tags = TagQuery::create()->find();
				foreach ($actualTags as $tag) {
					// salvo solo i tag nuovi, altrimenti la tabella dei TAG sarebbe piena di duplicati
		            $newTag = \TagQuery::create()->filterByName($tag)->findOneOrCreate();
		            //$tags->append($newTag);
					$offer->addTag($newTag);
			    }
			}

			unset($data->Tag);


			// imposto le azioni e il ritorno
			// in questo loop potrei incorporare una validazione automatica
			foreach( $data  as $key => $value) {
			    if($value == ''){
			     unset($data->{$key});
			     continue;
			 	}

			 	// trasformo il separatore dei decimali
			 	if(preg_match('/^\d+\,\d*$/', $value))	$value = preg_replace('@,@', '.', $value);
			 	
			 	$offer->{'set'.$key}($value); ;
			}

			if($proceed){
				$offer->save();
				//echo "salvato!\n";
				$return =json_encode(array('response'=>$proceed));
			}else{
				//echo "non puoi salvare";
				$return = json_encode(array('errors'=>$errors, 'response'=>$proceed));
			}

			echo $return;
	}

}

