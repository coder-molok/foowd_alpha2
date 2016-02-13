<?php

// per convenzione tutte le richieste ritornano valori in formato json;
// ciascuno lo stato dell'operazione e' sotto la chiave (o attributo decodificato) response
// type specifica quale metodo richiamare

namespace Foowd\FApi;
//use \Offer as Offer;
// use Base\OfferQuery as OfferQuery;
// use Base\TagQuery as TagQuery;
// use Propel\Runtime\ActiveQuery\Criteria;

// per ottenere la connessione a propel
use Propel\Runtime\Propel;

/**
 * @apiDefine MyResponseOrder
 *
 * @apiParam (Response) {Bool}				response 		false, in caso di errore
 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
 * @apiParam (Response) {String/json}		[body] 			json contenente i parametri da ritornare in funzione della richiesta
 * @apiParam (Response) {String} 			[msg/message] 	messaggi ritornati
 * 
 */


class ApiPurchase extends \Foowd\FApi{

	
	public function __construct($app, $method = null){

		parent::__construct($app, $method);

	}

	/**
	 *
	 * @api {post} /purchase create
	 * @apiName create
	 * @apiGroup Purchase
	 * 
 	 * @apiDescription Crea un nuova ordine assegnandogli stato "pending" di default. 
	 * 
	 * @apiParam {String} 		type 		create
	 * @apiParam {Numeric} 		OfferId		id dell'offerta
	 * @apiParam {Numeric} 		LeaderId 	id esterno del capogruppo
	 * @apiParam {String} 		prefersList	lista delle preferenze associate all'offerta
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *     		"type":"create",
	 *     		"OfferId":"1",
	 *       	"LeaderId": "38",
	 *         	"prefersList": "5,7"
	 *     }
	 *
	 * @apiUse MyResponseOffer
	 *     
	 */	
	public $needle_create = "OfferId, LeaderId, prefersList";
	public function create($data){
		$j = array();
		$j['response'] = false;
		$dataReceived = json_encode($data);

		// $order = new \Order();

		// imposto le date per il salvataggio
		date_default_timezone_set('Europe/Rome');
		if(!isset($data->Created)) $data->Created = date('Y-m-d H:i:s');
		if(!isset($data->Modified)) $data->Modified = date('Y-m-d H:i:s');

		// Check durante la raccolta
		
		// trasformo l'id da esterno a interno
		if(!$leaderId = $this->ExtToId($data->LeaderId)){
			$j['errors']['LeaderId']= "LeaderId esterno $data->ManagerId non presente";
		}

		// recupero l'offerta
		$offer = 	\OfferQuery::Create()
					->filterById($data->OfferId)
					->find();

		// verifico l'univocita' dell'offerta
		if( $offer->count() !== 1){
			$num  =  $offer->count();
			$j['errors']['OfferId'] = "OfferId risulta associato a $num offerte, mentre dovrebbe esistere ed essere univoco";
		}
		// hydrate: questo non riesegue una query al db
		$offer = \OfferQuery::Create()->filterById($data->OfferId)->findOne();

		// eventualmente potrei prelevare l'offerta per controllare che ci sia una quantita' massima da scalare
		// diciamo un parametro del tipo "scorte", in questo modo il produttore avrebbe:
		// minqt e maxqt come quantita' massima e minima che puo' inviare e poi il parametro "scorte" viene ad essere diminuito man mano che si vende
		

		// controllo sulle preferenze
		$prlist = array_map('trim', explode(',' , $data->prefersList));
		$prCount = count($prlist);

		// avendo tolto il giro delle 24h soltanto quelle nuove sono modificabili
		$editable = array('newest');

		// raccolgo le preferenze che sono editabili
		$prefers = \PreferQuery::Create()
					->filterById($prlist)
					->filterByState($editable)
					->find();

		// le raccolgo impostando i giusti parametri
		$prefs = array();
		$totalQt = 0;
		foreach($prefers as $pref){
			$totalQt += $pref->getQt();
			$id = $pref->getId();
			$prlist = array_diff($prlist, array($id));
			$p = $pref->toArray();
			$p['UserId'] = $this->IdToExt($p['UserId']);
			$prefs[] = $p;
		}

		$this->app->getLog()->warning($offer->toJson());
		$offerAr = $offer->toArray();

		if( count($prlist) > 0 ){
			$j['errors']['prefersList'] = "prefersList : gli id {".implode($prlist, ',')."} non corrispondono a preferenze salvate o editabili";
		}elseif($prCount !== $prefers->count()){
			// ad esempio per via del vincolo $editable ... e' un buon controllo di consistenza in fase di creazione
			$num = $prefers->count();
			$j['errors']['prefersList'] = "prefersList : la lista conta di $prCount elementi, mentre la ricerca mysql ne ha prodotti $num. Elementi non processabili: {".implode($prlist, ',')."}";
		}elseif($totalQt <= 0){
			$j['errors']['totalQt'] = "Errore nella quantita' totale conteggiata nelle preferenze. Risulta essere $totalQt";
		}elseif($totalQt > $offer->getMaxqt() && $offer->getMaxqt() > 0 ){ // se maxqt = 0 allora si presume illimitata
			$j['errors']['totalQt'] = "Errore nella quantita' totale conteggiata nelle preferenze. Risulta essere $totalQt, mentre la massima ordinabile e' ". $offer->getMaxqt();
		}

		// se l'offerta e' gia' scaduta
		if( !is_null($offerAr['Expiration']) && new \DateTime() > new \DateTime($offerAr['Expiration']) ){
			$j['errors']['Expiration'] = 'Impossibile completare l\'ordine: offerta scaduta';
		}

		if(isset($j['errors'])){
			$j['errors']['data'] = $dataReceived;	
			return $j;
		} 


		// spunto per performance e rollback (ovvero fare in modo che i dati tornino quelli originali in caso di errore)
		// http://propelorm.org/Propel/documentation/06-transactions.html

		$con = Propel::getConnection(\Map\PurchaseTableMap::DATABASE_NAME); // avrei potuto usare anche la preferenza per ottenere il nome del db
		$con->beginTransaction();


		// apro un blocco try - catch
		// in caso di errore, il rollback annulla tutte le operazioni di scrittura al DB dentro il blocco try: comodo!
		try{

			$order = new \Purchase();

			$order->setOfferId($data->OfferId);
			$order->setLeaderId($leaderId);
			$order->setCreated($data->Created);
			$order->setModified($data->Modified);


			// attualizzo le preferenze e le carico nell'ordine
			foreach ($prefers as $pref) {
				// aggiungo all'ordine le preferenze
				$order->addPrefer($pref);
				$pref->setState("pending");
				$this->Fvalidate($pref)->save($con);
			}

			$this->Fvalidate($order)->save($con);

		   	$con->commit();

		}
		catch (\Exception $e) {
		   $con->rollback();
		   throw $e;
		}

		// recupero i dati utili da ritornare come body:
		$o = $offer->toArray();
		$o['Publisher'] = $this->IdToExt($o['Publisher']);
		$j['body']['offer'] = $o;

		$j['body']['prefers'] = $prefs;

		// se sono qui tutto e' andato bene, pertanto ritorno solo un messaggio di successo
		$j['response']	 = true;
		$j['message'] = "salvataggio ordine e aggiornamento preferenze avvenuto con successo";

		return $j;

	}


	/**
	 *
	 * @api {post} /purchase solve
	 * @apiName solve
	 * @apiGroup Purchase
	 * 
 	 * @apiDescription  Dato l'id di un ordine lo chiude attualizzando le preferenze. <br/> Viene utilizzato un solo ordine per volta in modo da garantire una semplice e lineare gestione degli errori, preferendo svolgere piu chiamate di chiusura, avendo considerato che la chiusura di un'ordine e' un'operazione che non avviene poi cosi' di frequente.
	 * 
	 * @apiParam {String} 		type 		solve
	 * @apiParam {Numeric} 		PurchaseId	id dell'ordine, o lista di id separati da virgola
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *       "type":"solve",
	 *       "PurchaseId" : "158,12"
	 *     }
	 *
	 * @apiUse MyResponseOffer
	 *     
	 */	

	public $needle_solve = "PurchaseId";
	public function solve($data){
	
		$j = array();
		$j['response'] = false;
		$dataReceived = json_encode($data);

		// trasformo in array
		$purchIds = explode(',',$data->PurchaseId);

		$purchases = \PurchaseQuery::create()->filterById($purchIds);
		
		// $tmpQuery = $purchases->find();
		// if($tmpQuery->count() !== 1 ){
		// 	$j['error']['count'] = "Errore nel conteggio relativo all'ordine di Id $purchId : il conteggio di match risulta di ".$tmpQuery->count()." ordini";
		// 	return $j;
		// }

		// raccolgo i dati da aggiornare
		$purchases = $purchases->find();

		$con = Propel::getConnection(\Map\PurchaseTableMap::DATABASE_NAME); // avrei potuto usare anche la preferenza per ottenere il nome del db
		$con->beginTransaction();

		// salvo tutti i responsi in questo array:
		// nel caso durante il loop uno degli Id porti all'esecuzione dell'ecezione, 
		// per tenere traccia dei precedenti iter andati a buon fine e raccolti in questo array,
		// mi limito a stamparlo col logger
		$storePurch = array();
		foreach($purchases as $purch){
			// many-to-many : nota la "s" del plurale
			$prefers = $purch->getPrefers();

			// apro un blocco try - catch
			// in caso di errore, il rollback annulla tutte le operazioni: comodo!
			try{

				// nel caso in cui tutti gli utenti abbiano abbandonato l'ordine
				$totalQt = 0;
				
				// attualizzo le preferenze e le carico nell'ordine
				foreach ($prefers as $pref) {
					// la elimino (e essendo in cascade viene eliminata anche dalla tabella di join)
					if($pref->getQt() <= 0 ){
						$pref->delete($con);
						continue;
					} 
					$totalQt += $pref->getQt();
					$pref->setState("solved");
					$this->Fvalidate($pref)->save($con);
					$p = $pref->toArray();
					$p['UserId'] = $this->IdToExt($p['UserId']);
					// evito di mandare i dati superflui
					unset($p['Id']);
					unset($p['OfferId']);
					// dati da ritornare
					$j['body']['prefers'][] = $p;
				}

				if($totalQt <= 0 ){
					$_SESSION['foowd']['errors']['totalQt'] = $totalQt;
					$txt = "Totale di quote pari a $totalQt : probabilmente tutti gli utenti hanno abbandonato l'ordine";
					$txt.= "\nOra l'ordine e' impostato in stato \"troublesome\"";
					throw new \Exception($txt);
				}

				$purch->setState('solved');
				$this->Fvalidate($purch)->save($con);

			   	$con->commit();

			}
			catch (\Exception $e) {
			   $con->rollback();
			   // imposto in troublesome per evitare che nel cronjob di elgg venga riprocessato 
			   $er['completed'] = $storePurch;
			   $er['errors']['key'] = "storePurch";
			   $er['errors']['PurchaseId'] = $purch->getId();
			   $er['msg'] = 'errore durante la scrittura su DB di una purchase.';
			   // salvo per tenere traccia dell'eccezione
			   $this->app->getLog()->error(json_encode($er));
			   $purch->setState('troublesome');
			   $this->Fvalidate($purch)->save(); // nota che non uso la connessione
			   
			   throw new \Exception(json_encode($er), 1);
			}

			// preparo i dati da salver per ogni singola preferenza
			// ottengo i campi foreign
			// $purch->getUser()->toArray();
			// $purch->getOffer()->toArray();

			// dati da ritornare
			$p = $purch->toArray();
			$p['LeaderId'] = $this->IdToExt($p['LeaderId']);
			$p['totalQt'] = $totalQt;
			// offerta
			// $of = $purch->getOffer()->toArray();
			// $of['Publisher'] = $this->IdToExt($of['Publisher']);
			// $p['offer'] = $of;

			$storePurch[] = $p;

		} // end foreach purchases

		

		$j['response'] = true;
		$j['body'] = $storePurch;	

		return $j;


	}


	/**
	 *
	 * @api {get} /purchase search
	 * @apiName search
	 * @apiGroup Purchase
	 * 
 	 * @apiDescription Oltre a svolgere una ricerca nella tabella preferenze, ritorna anche il parametro extra "<strong>Offer</strong>" contenente l'offerta (in formato JSON) a cui ciascuna preferenza si riferisce.
 	 *
 	 * Strutturato in questo modo, cerca solo le intersezioni dei filtri.
	 * 
	 * @apiParamExample {url} URL-Example:
	 * 
	 * http://localhost/api_offerte/public_html/api/purchase?type=search&&State=newest,solved
	 * 
	 * @apiParam (Response) {Bool}				response 		false, in caso di errore
 	 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
 	 * @apiParam (Response) {String/json}		[body] 			json contenente i parametri da ritornare in funzione della richiesta. Il parametro prefer impostato nel ritorno contiene eventuali preferenze che metchano gli ExternalId passati con la chiamata.
 	 * @apiParam (Response) {String/json}		[body-array-prefers]	ogni array contiene la singola purchase, e prefers contiene l'elenco delle preferenze ad essa associate
	 * 
	 * @apiUse MyResponsePrefer
	 * 
	 */
	public static function search($data){
		
		unset($data->type);
		$Json = array();

		// trasformo gli ID da elgg a quelli DB
		$editId = array( 'LeaderId' , 'PublisherId' );
		foreach ($editId as $el) if(isset($data->{$el})) $data->{$el} = self::ExtToId($data->{$el});

		// se ho impostato un PublisherId, allora lo devo rimuovere perche' non posso usarlo come filtro
		if(isset($data->PublisherId)){
			$searchPub = $data->PublisherId;
			unset($data->PublisherId);
		}

		$obj = \PurchaseQuery::create();
		foreach($data as $key => $value){

			if(is_string($value) && preg_match('@{.+}@',$value)) $value = (array) json_decode($value);
			$obj = $obj->{'filterBy'.$key}($value);
		}

		$purchs = $obj->find();
		
		$body = array();
		foreach($purchs as $pur){

			// trovo l'offerta e i dati di interesse
			$of = $pur->getOffer()->toArray();

			// se ho il campo di ricerca, ma non combacia
			if(isset($searchPub) && $of['Publisher'] != $searchPub) continue;

			// prelevo l'ordine
			$prefs = $pur->getPrefers();
			$pur = $pur->toArray();

			// preparo i dati relativi alle preferenze
			$pur['prefers'] = array();
			$totalQt = 0;
			foreach($prefs as $pf){
				$pf = $pf->toArray();
				$totalQt += $pf['Qt'];
				$pf['UserId'] = self::IdToExt($pf['UserId']);
				$pur['prefers'][] = $pf;
			}

			$pur['totalQt'] = $totalQt;
			$pur['totalPrice'] = $totalQt * $of['Price'];
			// viene poi convertito nel foreach sottostante
			$pur['PublisherId'] = $of['Publisher'];
			$pur['OfferName'] = $of['Name'];



			foreach ($editId as $el) if(isset($pur[$el])) $pur[$el] = self::IdToExt($pur[$el]);			
			$body[] = $pur;
		}
		


		if(!isset($Json['response'])){ $Json['response'] = true;}
		else {$Json['errors'] = $msg; }
		$Json['body'] = $body;
		return $Json;
		
	}



	/**
	 *
	 * @api {post} /purchase search
	 * @apiName search
	 * @apiGroup Purchase
	 * 
 	 * @apiDescription ritorna tutte le ordinazioni filtrandole per stato.
 	 * 					Serve per il cronTab delle 24h
	 * 
	 * @apiParam {String} 		type 		create
	 * @apiParam {Numeric} 		OfferId		id dell'offerta
	 * @apiParam {Numeric} 		LeaderId 	id esterno del capogruppo
	 * @apiParam {String} 		prefersList	lista delle preferenze associate all'offerta
 	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *       "type":"search"
	 *       "state":"pending, opened",
	 *       "Publisher":"37",
	 *     }
	 *
	 * @apiUse MyResponseOffer
	 *     
	 */	
	/*
	public $needle_search = "State";
	public function search($data){
		$j = array();
		$j['response'] = false;
		// $dataReceived = json_encode($data);
		// trasformo la lista di stati in array
		// se non specificato, ricerco solo quelli con stato "newest"
		// eventualmente si puo' specificare State=all
		$editable = array('opened', 'pending');
		if(isset($data->State)){
			$state = $data->State;
			if(is_string($state) && preg_match('@,@', $state)) $data->State = array_map('trim' , explode( ',', $data->State) );
			if($state === 'editable') $state = $editable;
			if($state === 'all') unset($state);
		}
		$purcs = \PurchaseQuery::create();
		// inizio parte dei filtri in chain: per il momento ne uso solo uno
		if(isset($state)) $purcs = $purcs->filterByState($state);
		// faccio partire il find per ottenere la collezione
		$purcs = $purcs->find();
		// raccolto i risultati trasformandoli in utili per Elgg
		$purchAr = array();
		foreach($purcs as $p){
		 	$p = $p->toArray();
		 	$p['LeaderId'] = $this->IdToExt($p['LeaderId']);
		 	$purchAr[] = $p;
		}
		// dati da restituire
		$j['body']['purchases'] = $purchAr;
		$j['response'] = true;
		return $j;
	}*/


	
}

