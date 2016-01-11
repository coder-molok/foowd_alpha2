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

		date_default_timezone_set('Europe/Rome');
		if(!isset($data->Created)) $data->Created = date('Y-m-d H:i:s');
		if(!isset($data->Modified)) $data->Modified = date('Y-m-d H:i:s');

		// Check durante la raccolta
		
		// trasformo l'id da esterno a interno
		if(!$leaderId = $this->ExtToId($data->LeaderId)){
			$j['errors']['LeaderId']= "LeaderId esterno $data->ManagerId non presente";
		}

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

		if(isset($j['errors'])){
			$j['errors']['data'] = $dataReceived;	
			return $j;
		} 


		// spunto per performance e rollback (ovvero fare in modo che i dati tornino quelli originali in caso di errore)
		// http://propelorm.org/Propel/documentation/06-transactions.html

		$con = Propel::getConnection(\Map\PurchaseTableMap::DATABASE_NAME); // avrei potuto usare anche la preferenza per ottenere il nome del db
		$con->beginTransaction();


		// apro un blocco try - catch
		// in caso di errore, il rollback annulla tutte le operazioni: comodo!
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
		 * @api {post} /purchase search
		 * @apiName search
		 * @apiGroup Purchase
		 * 
	 	 * @apiDescription ritorna tutte le ordinazioni filtrandole per stato.
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
		 * @apiParam {Numeric} 		PurchaseId	id dell'ordine
	 	 * 
		 * @apiParamExample {json} Request-Example:
		 *     {
		 *       "type":"solve",
		 *       "PurchaseId" : "158"
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

			$purchId = $data->PurchaseId;

			$purchases = \PurchaseQuery::create()->filterById($purchId);
			
			$tmpQuery = $purchases->find();
			if($tmpQuery->count() !== 1 ){
				$j['error']['count'] = "Errore nel conteggio relativo all'ordine di Id $purchId : il conteggio di match risulta di ".$tmpQuery->count()." ordini";
				return $j;
			}

			// raccolgo i dati da aggiornare
			$purch = $purchases->findOne();
			// many-to-many : nota la "s" del plurale
			$prefers = $purch->getPrefers();

			$con = Propel::getConnection(\Map\PurchaseTableMap::DATABASE_NAME); // avrei potuto usare anche la preferenza per ottenere il nome del db
			$con->beginTransaction();


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
			   $purch->setState('troublesome');
			   $this->Fvalidate($purch)->save(); // nota che non uso la connessione

			   throw $e;
			}



			// ottengo i campi foreign
			// $purch->getUser()->toArray();
			// $purch->getOffer()->toArray();

			// dati da ritornare
			$p = $purch->toArray();
			$p['LeaderId'] = $this->IdToExt($p['LeaderId']);
			$p['totalQt'] = $totalQt;
			$j['body']['purchase'] = $p;

			$p = $purch->getOffer()->toArray();
			$p['Publisher'] = $this->IdToExt($p['Publisher']);
			$j['body']['offer'] = $p;

			$j['response'] = true;

			return $j;


		}

	
}

