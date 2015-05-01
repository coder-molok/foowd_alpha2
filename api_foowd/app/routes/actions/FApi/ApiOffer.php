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
	// public $needle  = array(
	// 		//"create"	=> "Name, Description, Price, Minqt, Publisher",
	// 		//"update"	=> "Name, Description, Price, Minqt, Publisher",
	// 		//"setState"	=> "Publisher, Id, State",
	// 		//"offerList" => "Publisher",
	// 		"delete"	=> "Publisher, Id",
	// 		//"single"	=> "Publisher, Id"

	// 	);


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
	public $needle_create = "Name, Description, Price, Minqt, Publisher, Tag";
	public function create($data){

		$offer = new \Offer();
		if(!isset($data->Created)) $data->Created = date('Y-m-d H:i:s');
		$this->ExtToId($data);
		return $this->offerManager($data, $offer);

	}

	/**
	 *
	 * @api {post} /offers update
	 * @apiName update
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Per aggiornare un' offerta. Sostanzialmente esegue le stesse operazioni di crea().
 	 *
 	 * Se non e' specificata la data di modifica, allora viene impostata all'ora attuale.
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
	 * @apiParam {String} 		Name 		nome offerta, ovvero il titolo
	 * @apiParam {String/html} 	Description descrizione offerta, 
	 * @apiParam {Numeric} 		Price 		prezzo
	 * @apiParam {Numeric} 		Minqt 		quantita' minima
	 * @apiParam {Numeric} 		[Maxqt] 	quantita' massima
	 * @apiParam {String} 		[Tag] 		lista dei tag
	 * @apiParam {Date} 		[Created] 	funzione php: date('Y-m-d H:i:s');
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
	public $needle_update = "Name, Description, Price, Minqt, Publisher, Tag";
	protected function update($data){

		$this->ExtToId($data);

		// recupero il post originale
		$offer = \OfferQuery::create()
		  		->filterById($data->Id)
		  		->filterByPublisher($data->Publisher)
		  		->findOne();

		if(!$offer) {
			return array("errors"=>"Id e Publisher non sono associati a un'offerta esistente.", "response" => false);
		}

		// cancello tutti i tag per riscrivere quelli aggiornati
		\OfferTagQuery::create()
				->filterByOfferId($data->Id)
				->delete();

		//date_timezone_set('Europe/Rome');
		if(!isset($data->Modified)) $data->Modified = date('Y-m-d H:i:s');

		return $this->offerManager($data, $offer);
	}

	/**
	 *
	 * @api {post} /offers setState
	 * @apiName setState
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Modifica lo stato di un'offerta. 
	 * 
	 * @apiParam {String} 		type 		metodo da chiamare
	 * @apiParam {Integer}  	Id 			id dell'offerta
	 * @apiParam {Integer}  	Publisher 	id dell'offerente
	 * @apiParam {Enum}  		State 		{open,close}: stato dell'offerta
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *    {
	 *     "Id":"88",
	 *     "Publisher":"5",
	 *     "State": "close",
	 *     "type":"setState"
	 *    }
	 *
	 * @apiUse MyResponse
	 *     
	 */
	public $needle_setState = "Publisher, Id, State";
	protected function setState($data){
		foreach($data as $key => $value){
			if(!preg_match('@'.$key.'@', $this->needle['setState'])){
				unset($data->{$key});
			}
		}
		return $this->update($data);
	}

	


	/**
	 *
	 * @api {get} /offers search
	 * @apiName search
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Per ottenere la lista delle offerte di un dato Publisher.
 	 *
 	 * Strutturato in questo modo, cerca solo le intersezioni dei filtri.
	 * 
	 * @apiParam {String} 		type 			metodo da chiamare
	 * @apiParam {Mixed}	  	[qualunque] 	qualunque colonna. Il valore puo' essere una STRINGA o un ARRAY come stringa-JSON con chiavi "max" e/o "min" (lettere minuscole).
	 * @apiParam {String} 		[order] 		stringa per specificare l'ordinamento. Il primo elemento e' la colonna php. Si puo' specificare se 'asc' o 'desc' inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified
	 * @apiParam {Mixed}	  	[offset] 		Il valore puo' essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi "page" e "maxPerPage" per sfruttare la paginazione di propel.
	 * @apiParam {String} 		[Tag] 			elenco di tags separati da virgola
	 *
	 * @apiParamExample {url} URL-Example:
	 * 
	 * http://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&type=search&Id={"min":2 ,"max":109}&Tag=mangiare, cibo&order=Modified, desc
	 * 
	 * @apiUse MyResponse
	 * 
	 */
	protected function search($data){

		// converto il Publisher con Id elgg a Publisher con Id User
		$this->ExtToId($data, 1);

		// unset($data->type);
		// unset($data->method);
		if(isset($data->Tag)){
			//var_dump($data->Tag);
			$Tag = array_map('trim', explode(',', $data->Tag));
			unset($data->Tag);
			//var_dump($Tag);
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

		$obj = \OfferQuery::create();
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
			$offer = $obj;

		}else{

			$offer = $obj->find();
		
		}
		
		
		$return = array();
		
		foreach ($offer as $single) {

			$ar = $single->toArray();
			$tgs = $single->getTags();
			$ar['Tag'] ='';
			$tmp = array();
			foreach ($tgs as $value) {
				//var_dump($value->getName());
				//$ar['Tag'] .= $value->getName().', ';
				array_push($tmp, trim($value->getName()));
			}
			$ar['Tag'] = implode($tmp,', ');

			// nel caso la ricerca contempli i tag
			if(isset($Tag)){
				// in questo modo aggiungo solo le offerte che contengono TUTTI i tag elencati
				$i = 0;
				foreach($Tag as $value){
					if(preg_match('@'.$value.'@', $ar['Tag'])) $i++;	
				}
				if($i == count($Tag)){array_push($return, $ar);}
			}else{
				array_push($return, $ar);
			}

			// Aggiungo tutti i post che contengono ALMENO uno dei tag
			// foreach($Tag as $value){
			// 	if(preg_match('@'.$value.'@', $ar['Tag'])) array_push($return, $ar);
			// }


		}

		// if(count($return ) == 0) $Json['response'] =false;
		if(!isset($Json['response'])){ $Json['response'] = true;}
		else {$Json['errors'] = $msg; }
		$Json['body'] = $return;
		return $Json;
		
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
	public $needle_delete = "Publisher, Id";// State
	protected function delete($data){

		$this->ExtToId($data);

		$offer = \OfferQuery::create()
		  	->filterById($data->Id)
		  	->filterByPublisher($data->Publisher)
		 	->find();

		 $status = false;

		 // in teoria la query dovrebbe restituire un solo valore, ma meglio controllare
		 if( $offer->count() == 1){
		 	$offer->delete();
		 	$status = true;
		 }else{
		 	$Json['errors'] = "Si sta tentando di cancellare un post che non esiste.";
		 }

		 $Json['response'] = $status;
		 
		//echo json_encode($count);
		return $Json;	
	}



	/**
	 *
	 * @api {get} /offers searchPrefer
	 * @apiName searchPrefer
	 * @apiGroup Offers
	 * 
 	 * @apiDescription Svolge la normale Search aggiungendo la chiave "prefer" alle offerte ritornate per le quali ExternalId abbia espresso una preferenza:
 	 * 		in tal caso "prefer" contiene tutti i dati relativi alla preferenza.
 	 * 		Se non si presenta tale corrispondenza, allora la singola offerta non contiene la chiave "prefer".
 	 *
 	 * Strutturato in questo modo, cerca solo le intersezioni dei filtri.
	 * 
	 * @apiParam {String} 		type 			searchPrefer
	 * @apiParam {Integer} 		ExternalId		User Id elgg (ovvero ExternalId API) dell'utente che sta' eseguendo la ricerca
	 * @apiParam {Mixed}	  	[qualunque] 	qualunque colonna. Il valore puo' essere una STRINGA o un ARRAY come stringa-JSON con chiavi "max" e/o "min" (lettere minuscole).
	 * @apiParam {String} 		[order] 		stringa per specificare l'ordinamento. Il primo elemento e' la colonna php. Si puo' specificare se 'asc' o 'desc' inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified
	 * @apiParam {Mixed}	  	[offset] 		Il valore puo' essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi "page" e "maxPerPage" per sfruttare la paginazione di propel.
	 * @apiParam {String} 		[Tag] 			elenco di tags separati da virgola
	 *
	 * @apiParamExample {url} URL-Example:
	 * 
	 * http://localhost/api_foowd/public_html/api/offer?type=searchPrefer&ExternalId=52&Publisher=5&Tag=latticini
	 * 
	 * @apiUse MyResponse
	 * 
	 */
	
	public $needle_searchPrefer = "ExternalId";
	public function searchPrefer($data){
		if(!isset($data->ExternalId)){
		 $errors['ExternalId'] = "Impossibile eseguire il metodo senza ExternalId";
		 $Json['response'] = false;
		}else{
			// recupero lo userId e poi elimino $data->ExternalId
			$UserId = \UserQuery::Create()->filterByExternalId($data->ExternalId)->findOne();
			// se l'utente con quell'id esterno esiste, allora lo utilizzo, altrimenti blocco tutto
			if(is_object($UserId)){
				$UserId = $UserId->getId();
				$ExternalId = $data->ExternalId;
				unset($data->ExternalId);
			}else{
				$Json['response'] = false;
				$Json['errors']['ExternalId'] = "L'ExternalId  non e' associato a nessun utente API";
				$Json['errors']['File'] = __FILE__. ' Line: '.__LINE__;
				echo json_encode($Json);
				exit(7);
			}

			// ora eseguo la ricerca delle offerte in base ai filtri passati:
			
			$search = $this->search($data);
			$ext = new \stdClass();
			$ext->ExternalId = $ExternalId;
			
			// per ogni offerta, controllo se esiste la preferenza: se si, imposto true, altrimenti false
			foreach ($search['body'] as $key => $offer) {
			
				$ext->OfferId = $offer['Id'];
				$match = \Foowd\Fapi\ApiPrefer::search($ext);
				if(isset($match['body'])){
					if(count($match['body'])== 1){
						$search['body'][$key]['prefer'] = $match['body'][0];
					}else{
						$Json['respnse'] = false;
						$errors['conteggio'] = "Errore: hai piu' preferenze dello stesso utente associate al singolo post";
						$errors['file'] = "File: ".__FILE__." , Line: ".__LINE__;
					}
				}
			}
			// echo "<pre>";
			// print_r($search);
			// echo "</pre>";
			$Json['body'] = $search;
		}

		if(!isset($Json['response'])){ $Json['response'] = true;}
		else {$Json['errors'] = $errors; }
		return $Json;
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
			// unset($data->type);
			// unset($data->method);

			// memorizzo i singoli errori
			$errors = array();
			$proceed = true;


			// check foreign constraint
			$fkId = \UserQuery::create()->findOneById($data->Publisher);
			if(!$fkId){
				$proceed = false;
				$errors['Foreign']['Id'] = "Errore: il Publisher non risulta nella tabella Utenti";
				$errors['Foreign']['File'] = "File: ".__FILE__." , Line: ".__LINE__;
			}

			if(isset($data->Tag)){
				// prendo i tag inseriti dal form
				$actualTags = array_map('trim', explode(',', $data->Tag));
				$actualTags = array_unique($actualTags);	// evito eventuali ripetizioni
				//$errors['Tag'] = array();
	
				// valido i tag (posso anche impostarlo lato propel)
				foreach ($actualTags as $single) {
					// prima di salvare, controllo che il tag sia di una sola parola
					// da vedere: aggiungere controllo sulla presenza di caratteri speciali
					$single = trim($single);
					if(preg_match('@ +@i', $single)) $errors['Tag'][$single] = "I tag possono essere costituiti da una sola parola: ".$single;
					if($single == '' ) $errors['Tag']['empty'] = "I tag possono essere parole vuote: controlla che non vi sia una virgola iniziale o finale";
				}
				if(isset($errors['Tag'])) $proceed = false;
			}

			// i settaggi li faccio solamente se tutti i controlli precedenti sono andati a buon fine 
			if($proceed){
				unset($errors['Tag']);	
				
				//$tagManager = OfferTagQuery::create()->find();
				//$tags = TagQuery::create()->find();
				if(isset($actualTags)){
					foreach ($actualTags as $tag) {
						// salvo solo i tag nuovi, altrimenti la tabella dei TAG sarebbe piena di duplicati
			            $newTag = \TagQuery::create()->filterByName($tag)->findOneOrCreate();
			            //$tags->append($newTag);
						$offer->addTag($newTag);
				    }
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
			 	//var_dump($data->{$key});

			 	// trasformo il separatore dei decimali
			 	if(preg_match('/^\d+\,\d*$/', $value))	$value = preg_replace('@,@', '.', $value);
			 	//var_dump("{'set'.$key}($value)");
			 	$offer->{'set'.$key}($value); 
			 	//var_dump($offer->{'get'.$key}($value));
			}

			if($proceed){
				$return = $this->FSave($offer);
			}else{
				//echo "non puoi salvare";
				$return = array('errors'=>$errors, 'response'=>$proceed);
			}

			return $return;
	}


	
	/**
	 * tutti i Publisher che arrivano da ELGG in realta' sono gli ExternalId,
	 * pertanto ridefinisco il Publisher in mase all' ID associato nella tabella User.
	 * @param [type] $data [description]
	 * @param [type] $noEr se impostato, gli dico di non considerare errore la non corrispondenza(vedi Search)
	 */
	protected function ExtToId($data, $noEr =null){

		if(isset($data->Publisher)){
			$data->Publisher = \UserQuery::Create()->filterByExternalId($data->Publisher)->findOne();
			if(is_object($data->Publisher)){
				$data->Publisher = $data->Publisher->getId();
			}else{
				if($noEr === null){
					$Json['response'] = false;
					$Json['errors']['Foreign'] = "L'id passato non e' associato a nessun utente API";
					$Json['errors']['File'] = __FILE__. ' Line: '.__LINE__;
					echo json_encode($Json);
					exit(7);
				}
			}
		}
	}


}

