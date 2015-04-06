<?php

// per convenzione tutte le richieste ritornano valori in formato json;
// ciascuno lo stato dell'operazione e' sotto la chiave (o attributo decodificato) response
// type specifica quale metodo richiamare

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


class ApiOffer extends \Foowd\FApi{

	// la chiave e' il nome del metodo, i valori sono i dati obbligatori separati da virgola
	// i dati associati ai campi del DB devono essere indicati secondo il nome php specificato nello schema xml.
	public $needle  = array(
			//"create"	=> "type, Name, Description, Price, Minqt, Publisher",
			"update"	=> "type, Name, Description, Price, Minqt, Publisher",
			"offerList" => "type, Publisher",
			"delete"	=> "type, Publisher, Id",
			"single"	=> "type, Publisher, Id"

		);

	// da rivedere> automatizzare foreign constraint


	public function __construct($app, $method = null){

		parent::__construct($app, $method);

	}

	/**
	 *
	 * @api {post} /offers create
	 * @apiName create
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Crea una nuova offerta. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {String} 		Name 		nome offerta, ovvero il titolo
	 * @apiParam {String/html} 	Description descrizione offerta, 
	 * @apiParam {Numeric} 		Price 		prezzo
	 * @apiParam {Numeric} 		Minqt 		quantita' minima
	 * @apiParam {Numeric} 		[Maxqt] 	quantita' massima
	 * @apiParam {String} 		[Tag] 		lista dei tag
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *       "Name":"Salumi a Go Go!",
	 *       "Description":"una bella cassa di salumi, buona buona",
	 *       "Price":"7,25",
	 *       "Minqt":"5",
	 *       "Maxqt":"20",
	 *       "Tag":"cibo, mangiare, salumi, affettati",
	 *       "Created":"2015-03-20 19:07:55",
	 *       "Publisher":"37",
	 *       "type":"create"
	 *     }
	 *
	 * @apiUse MyResponse
	 *     
	 */	
	public function create($data){

		$offer = new \Offer();
		$this->offerManager($data, $offer);

	}

	/**
	 *
	 * @api {post} /offers update
	 * @apiName update
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Per aggiornare un' offerta. Sostanzialmente esegue le stesse operazioni di crea().
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {String} 		Name 		nome offerta, ovvero il titolo
	 * @apiParam {String/html} 	Description descrizione offerta, 
	 * @apiParam {Numeric} 		Price 		prezzo
	 * @apiParam {Numeric} 		Minqt 		quantita' minima
	 * @apiParam {Numeric} 		[Maxqt] 	quantita' massima
	 * @apiParam {String} 		[Tag] 		lista dei tag
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *      "Id":"38",
	 *      "Name":"Salumi a Go Go!",
	 *      "Description":"una bella cassa di salumi, ecceziunali veramente!",
	 *      "Price":"7,56",
	 *      "Minqt":"3",
	 *      "Maxqt":"10",
	 *      "Tag":"cibo, mangiare, latticini",
	 *      "Modified":"2015-03-20 19:14:17",
	 *      "Publisher":"37","type":"update"
	 *     }
	 *
	 * @apiUse MyResponse
	 *     
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
	 *
	 * @api {get} /offers offerList
	 * @apiName offerList
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Per ottenere la lista delle offerte di un dato Publisher.
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
	 *
	 * @apiParamExample {url} URL-Example:
	 * http://localhost/api_offerte/public_html/api/offers?type=offerList&Publisher=37
	 *
	 * @apiUse MyResponse
	 * 
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
	 *
	 * @api {post} /offers delete
	 * @apiName delete
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Per eliminare un' offerta. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
	 * @apiParam {Integer}  	Id 			id dell'offerta
	 *
	 * @apiParamExample {json} Request-Example:
	 * {
	 * 	"Publisher":"37",
	 * 	"Id":"30",
	 * 	"type":"delete"
	 * }
	 *
	 * @apiUse MyResponse
	 * 
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

	/**
	 *
	 * @api {get} /offers single
	 * @apiName single
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Per ottenere l'offerta specifica di un utente. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
	 * @apiParam {Integer}  	Id 			id dell'offerta
	 *
	 * @apiParamExample {url} Request-Example:
	 * http://localhost/api_offerte/public_html/api/offers?Publisher=37&Id=31&type=single
	 *
	 * @apiUse MyResponse
	 * 
	 */
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
				foreach(\TagQuery::create()->filterById($value->getId())->find() as $t){
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


			// check foreign constraint
			$fkId = \UserQuery::create()->findOneById($data->Publisher);
			if(!$fkId){
				$proceed = false;
				$errors['Foreign']['Id'] = "Errore: il Publisher non risulta nella tabella Utenti";
			}

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
					$errors['Tag'][$single] = "errore nel tag: ".$single;
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
			 	
			 	//var_dump("{'set'.$key}($value)");
			 	$offer->{'set'.$key}($value); 
			}

			if($proceed){
				//echo "salvato!\n";
				$offer->save();
				$return =json_encode(array('response'=>$proceed));
			}else{
				//echo "non puoi salvare";
				$return = json_encode(array('errors'=>$errors, 'response'=>$proceed));
			}

			echo $return;
	}

}

