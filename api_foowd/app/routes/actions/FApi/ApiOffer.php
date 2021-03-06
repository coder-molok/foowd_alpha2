<?php

// per convenzione tutte le richieste ritornano valori in formato json;
// ciascuno lo stato dell'operazione e' sotto la chiave (o attributo decodificato) response
// type specifica quale metodo richiamare

namespace Foowd\FApi;
//use \Offer as Offer;
// use Base\OfferQuery as OfferQuery;
// use Base\TagQuery as TagQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * @apiDefine MyResponseOffer
 *
 * @apiParam (Response) {Bool}				response 	false, in caso di errore
 * @apiParam (Response) {String}			[Id]	 	se il metodo e' update o create, allora l'id dell'offerta
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
	 * @api {post} /offer create
	 * @apiName create
	 * @apiGroup Offer
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
	 * @apiUse MyResponseOffer
	 *     
	 */	
	public $needle_create = "Name, Description, Price, Publisher, Tag";//Minqt, Maxqt, 
	public function create($data){

		//--- imposizione singola offerta per produttore
		$pub = self::ExtToId($data->Publisher);
		$of = \OfferQuery::create()->filterByPublisher($pub)->find();
		if($of->count() > 99) return [
				'response' => false,
				'singleOfferError' => 'Attualmente puoi creare una sola offerta.' // vedi anche actions/add lato elgg
			];
		//--- fine blocco

		$offer = new \Offer();
		date_default_timezone_set('Europe/Rome');
		if(!isset($data->Created)) $data->Created = date('Y-m-d H:i:s');
		if(!isset($data->Modified)) $data->Modified = date('Y-m-d H:i:s');
		$this->Ext2Id($data);
		return $this->offerManager($data, $offer);

	}

	/**
	 *
	 * @api {post} /offer update
	 * @apiName update
	 * @apiGroup Offer
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
	 * @apiUse MyResponseOffer
	 *     
	 */
	public $needle_update = "Name, Description, Price, Publisher, Tag"; //Minqt, Maxqt, 
	protected function update($data){

		$this->Ext2Id($data);

		// recupero il post originale
		$offer = \OfferQuery::create()
		  		->filterById($data->Id)
		  		->filterByPublisher($data->Publisher)
		  		->findOne();

		if(!$offer) {
			return array("errors"=>"Id e Publisher non sono associati a un'offerta esistente.", "response" => false);
		}

		// cancello tutti i tag per riscrivere quelli aggiornati
		// $tg = \OfferTagQuery::create()->filterByOfferId($data->Id)->delete();
		
		//////////// Preparo i Tag per passarse a offerManager solo quelli da aggiungere.
		//////////// nel mentre quelli superflui li elimino
		// al posto della query sopra posso creare un loop usando direttamente $offer:
		// $Tg e' un oggetto Tag di propel

		$oldTg = array();
		$newTag = array_map('trim', explode(',' , $data->Tag));
		foreach($offer->getTags() as $Tg) {
			// con la seconda condizione evito di cancellare nel caso di parole separate da spazi:
			// se ho gia' "formaggi" e inserisco "formaggi sardi", senza la virgola, devo evitare che il tag
			// "formaggi" venga cancellato: il modo piu' semplice e' controllare se era gia' presente
			// nella stringa di partenza, ovvero $data->Tag
			if(in_array($Tg->getName(), $newTag) || preg_match('@'.$Tg->getName().'@', $data->Tag)){
				// var_dump($Tg->getName().' gia presente');
				array_push($oldTg, $Tg->getName());
			}else{
				// var_dump($Tg->getName().' lo elimino');
				$Tg->delete();
			}
		}

		// raccolgo solo i nuovi valori
		$data->Tag = implode(' , ' , array_diff($newTag, $oldTg) );
		// e se non ne ho di nuovi, lo rimuovo per non creare confusione in offerManager
		if($data->Tag == '') unset($data->Tag);

		// il tag Modified si riaggiorna in automatico grazie ai parametri ON UPDATE inseriti in mysql
		// $offer->setModified('');
		// \OfferQuery::create()->filterById($data->Id)->update(array('Name'=>$data->Name));

		date_default_timezone_set('Europe/Rome');
		if(!isset($data->Modified)) $data->Modified = date('Y-m-d H:i:s');

		// se non e' impostata, vuol dire che e' stata rimossa manualmente dal form, pertanto la reimposto
		if(!isset($data->Expiration)){
			$data->Expiration = null;
		} 
		
		return $this->offerManager($data, $offer);
	}

	/**
	 *
	 * @api {post} /offer setState
	 * @apiName setState
	 * @apiGroup Offer
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
	 * @apiUse MyResponseOffer
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
	 * @api {get} /offer search
	 * @apiName search
	 * @apiGroup Offer
	 * 
 	 * @apiDescription Per ottenere la lista delle offerte mediante filtri, in particolare cerca solo le intersezioni dei filtri.
	 * 
	 * @apiParam {String} 		type 			metodo da chiamare
	 * @apiParam {Mixed}	  	[qualunque] 	qualunque colonna. Il valore puo' essere una STRINGA o un ARRAY come stringa-JSON con chiavi "max" e/o "min" (lettere minuscole).
	 * @apiParam {String} 		[order] 		stringa per specificare l'ordinamento. Il primo elemento e' la colonna php. Si puo' specificare se 'asc' o 'desc' inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified
	 * @apiParam {Mixed}	  	[offset] 		Il valore puo' essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi "page" e "maxPerPage" per sfruttare la paginazione di propel.
	 * @apiParam {Mixed}	  	[match] 		Stringa-JSON le cui chiavi sono le colonne del DB e i valori sono singole parole separate da spazi o virgole.
	 * @apiParam {String} 		[Tag] 			elenco di tags separati da virgola, o stringa di lunghezza nulla ''
	 * @apiParam {Str/Num}		[ExternalId] 	numero intero o sequenza di interi separati da virgola. Rappresenta/no id dell'utente: per ogni offerta ritornata, il campo "prefer" sara' riempito con le preferenze della singola offerta che matchano gli id ivi passati.
	 *
	 * @apiParam {string} 		[excludeId] 	elenco di id separati da virgola. Se impostato esclude quegli id dal campo di ricerca
	 *
	 * @apiParamExample {url} URL-Example:
	 * 
	 * http://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&type=search&Id={"min":2 ,"max":109}&Tag=mangiare, cibo&order=Modified, desc&ExternalId=52,37
	 * 
	 * @apiParam (Response) {Bool}				response 		false, in caso di errore
 	 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
 	 * @apiParam (Response) {String/json}		[body] 			json contenente i parametri da ritornare in funzione della richiesta. Il parametro prefer impostato nel ritorno contiene eventuali preferenze che metchano gli ExternalId passati con la chiamata.
 	 * @apiParam (Response) {String/json}		[body-totalQt]	ogni preferenza ritornata contiene la Quantinta' totale ad essa associata. 0 nel caso non vi siano preferenze espresse per essa, o qualora valgano effettivamente zero.
	 * @apiParam (Response) {Array/json}		[body-prefer]	La preferenza con il totale gruppo ed eventualmente la lista degli id delle preferenze degli amici se vegono forniti piu external id
 	 * @apiParam (Response) {String} 			[msg] 			messaggi ritornati
	 * 
	 */
	protected function search($data){

		// converto il Publisher con Id elgg in Publisher con Id User
		$this->Ext2Id($data, 1);

		// unset($data->type);
		// unset($data->method);
		
		if(isset($data->Tag)){
			//var_dump($data->Tag);
			$Tag = array_map('trim', explode(',', $data->Tag));
			unset($data->Tag);
			//var_dump($Tag);
		}

		if(isset($data->Id)){
			$value = $data->Id;
			// nel caso sia una semplice lista
			if(is_string($value) && preg_match('@[^"]*@',$value)) $data->Id = explode(',' , $value);
		}

		if(isset($data->order)){
			$order = array_map('trim' , explode( ',', $data->order) );
			// imposto asc come default
			if(!isset($order[1])) $order[1]= 'asc';
			//var_dump($order);
			unset($data->order);
		}

		if(isset($data->excludeId)){
			$excludeId = array_map('trim' , explode( ',', $data->excludeId) );
			unset($data->excludeId);
		}

		if(isset($data->offset)){
			if(preg_match('@{.+}@',$data->offset)){
				$offset = (array) json_decode($data->offset);
			}else{
				$offset = $data->offset;
			}
			unset($data->offset);
		}

		if(isset($data->Expiration)){
			if(preg_match('@{.+}@',$data->Expiration)){
				$expiration = (array) json_decode($data->Expiration);
			}
			unset($data->Expiration);
		}

		if(isset($data->ExternalId)){
			$data->ExternalId = trim($data->ExternalId, ',');
			if(preg_match('@,@',$data->ExternalId)){
				$toCheck = explode(',' , $data->ExternalId);
			}else{
				$toCheck = array($data->ExternalId);
			}
			$localId = [];
			unset($data->ExternalId);

			foreach($toCheck as $value){
				// recupero lo userId e poi elimino $data->ExternalId
				$UserId = \UserQuery::Create()->filterByExternalId($value)->findOne();
				// se l'utente con quell'id esterno esiste, allora lo utilizzo, altrimenti blocco tutto
				if(is_object($UserId)){
					// questo dato verra' riutilizzato nel loop delle offerte
					$ExternalId[] = $value;
					$localId[] = $UserId->getId();
				}else{
					$Json['response'] = false;
					$Json['errors']['ExternalId'][] = "L'ExternalId ".$value."  non e' associato a nessun utente API";
					$Json['errors']['File'] = __FILE__. ' Line: '.__LINE__;
				}
			}

			if(!isset($ExternalId) || count($ExternalId)!==count($toCheck)){
				echo json_encode($Json);
				exit(7);
			}
		}

		// NB: se ritorna qualche errore sul Model Criteria e' perche' probabilmente sto' usando un dato di ricerca che non esiste!
		//var_dump($data);

		$obj = \OfferQuery::create();
		foreach($data as $key => $value){
			// echo "$key";
			if(is_string($value) && preg_match('@{.+}@',$value)) $value = (array) json_decode($value);

			// al match applico il filtro per condizione or
			if($key === 'match'){
				// la $k e' il campo in cui cercare la presenza delle parole chiave,
				// tipicamente il titolo dell'offerta o il testo.
				$j = 0;
				$conds = array();
				foreach($value as $k => $val){
					$val = preg_split( "/(,|'| )/", $val );

					if($k == "Tag"){
						$matchTag = $val;
						continue;
					}
					// se sono vuoti o minori di tre li elimino
					foreach($val as $i => $v){
						if( strlen($v)>2 ) {
							$cond = 'cond'.$j;
							$conds[] = $cond;
							// utf8_ci serve per avere le condizioni case insensitive COLLATE utf8_general_ci'
							$obj = $obj->condition($cond, 'Offer.'.$k.' LIKE ?', '%'.$v.'%');
							$j++;
						}
					}
				}
				if($j>0) $obj = $obj->where($conds, 'or');
				continue;
			}

			$obj = $obj->{'filterBy'.$key}($value);
		}

		if(isset($expiration)){
			$obj = $obj->filterByExpiration($expiration)->_or()->filterByExpiration(NULL);
		}

		// oppure applico un filtro by tag, con condizione or... devo usare la tabella many-to-many
		if(isset($matchTag)){
			$matchTag[] = '';
			//NB: da questa query le offerte non relazionate ad alcun tag vengono escluse:
			//in fondo uso una relazione many to many!
			$obj = $obj->_or()->useOfferTagQuery()
							->_or()
							->useTagQuery()
								->filterByName($matchTag)
							->endUse()
						->endUse();
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
				$obj = $obj->limit($offset);				
			}
		}

		// per fare una ricerca incrementale, nel wall, evito di continuare a richiedere relativamente ai post che avevo gia' ottenuto
		if(isset($excludeId)){
			// error_log(json_encode($excludeId));
			// $obj = $obj->filterById($excludeId, Criteria::CONTAINS_NONE );
			$obj->where('offer.id NOT IN ?', $excludeId);
		}

		$offer = $obj->find();


		//-- ora lavoro sul risultato per il vincolo "singola offerta per ciascun produttore"
		// 		a breve questo procedimento verra' rimosso
		
		// raggruppo per produttore e tengo quella con Id piu basso (ovvero rispecchio l'ordine cronologico)
		$publs = [];
		$tmpOffer = [];
		$oneOfPerPublisher = [];
		foreach($offer as $key => $s){
			$tmpOffer[$key] = $s;
			$pub = $s->getPublisher();
			$id = $s->getId();
			// se non era nell'array, allora lo salvo e continuo
			if(!isset($publs[$pub])){
				$publs[$pub] = [
					'id' => $id,
					'key' => $key
				];
				continue;
			}
			$oldId = $publs[$pub]['id'];
			$removeK = max($oldId, $id);
			// se devo rimuovere l'attuale la rimuovo e basta
			if($removeK == $id){
				unset($tmpOffer[$key]);
			}else{
				// rimuovo quello vecchio
				unset($tmpOffer[$publs[$pub]['key']]);
				// se rimuovo il vecchio, devo attualizzare i valori
				$publs[$pub]['id'] = $id;
				$publs[$pub]['key'] = $key;
			}
		}
		$offer = $tmpOffer;
		// le raccolgo in un array che utilizzero' per il loop sui groups
		foreach($offer as $o) $oneOfPerPublisher[] = $o->getId();
		//-- fine modalita' singolo
		
		
		$return = array();

		// array che conterra' i dati dei gruppi di offerte
		// (attualmente i gruppi sono solo TUTTE le offerte di ciascun produttore)
		$groups = array( "PublisherId" => array() );
	
		// ciclo su ogni offerta
		foreach ($offer as $single) {

			// array in cui salvo la risposta in formato 'offer' => , 'prefers' =>
			$singleEntry = array();

			$ar = $single->toArray();

			// ricavo il nome dell'azienda
			$usr = $single->getUser();
			$ar['Company'] = $usr->getCompany();

			// salvo l'id per il loop sul gruppo
			if(!in_array($usr->getId(), $groups['PublisherId'])) $groups['PublisherId'][] = $usr->getId();

			// cerco le quantita' totali associate alla singola offerta
			$ext = new \stdClass();
			$ext->OfferId = $single->getId();		

			// Restituisco l'id esterno dell'utente, ovvero quello utilizzato da Elgg
			$ar['Publisher'] = $this->IdToExt($ar['Publisher']);
			if(isset($ar['UserId'])) $ar['UserId'] = $this->IdToExt($ar['UserId']);

			// se l'utente ha espresso una preferenza per questo prodotto, allora la aggiungo come prefer, altrimenti risulta null
			if(isset($ExternalId)){
				$prefers = $single->getPrefers()->toArray();
				$pfs = array();
				foreach($prefers as $p){
					// riimposto l'id a esterno e poi lo controllo
					$p['UserId'] = self::IdToExt($p['UserId']);
					if( in_array($p['UserId'], $ExternalId) ) $pfs[] = $p;
				}
				// voce della risposta
				$singleEntry['prefers'] = $pfs;
			
			}
			

			// ora lavoro sui tags
			$tgs = $single->getTags();
			$ar['Tag'] ='';
			$tmp = array();
			foreach ($tgs as $value) {
				//var_dump($value->getName());
				//$ar['Tag'] .= $value->getName().', ';
				array_push($tmp, trim($value->getName()));
			}
			$ar['Tag'] = implode($tmp,', ');

			// aggiungo un tag di default, altrimenti nella query _or() dei tags non filtra perche' cosi' questo prodotto non risulta relazionato ad alcun tag
			if($ar['Tag'] == ''){
				$ntg = new \Tag();
				$ntg->setName('foowd');
				$single->addTag($ntg);
				$single->save();
			}
			// nel caso la ricerca contempli i tag
			// if(isset($Tag)){
			// 	// in questo modo aggiungo solo le offerte che contengono TUTTI i tag elencati
			// 	$i = 0;
			// 	foreach($Tag as $value){
			// 		if(preg_match('@'.$value.'@', $ar['Tag'])) $i++;	
			// 	}
			// 	$singleEntry['offer'] = $ar;
			// 	if($i == count($Tag)){array_push($return, $singleEntry);}
			// }else{
				$singleEntry['offer'] = $ar;
				array_push($return, $singleEntry);
			// }

			// Aggiungo tutti i post che contengono ALMENO uno dei tag
			// foreach($Tag as $value){
			// 	if(preg_match('@'.$value.'@', $ar['Tag'])) array_push($return, $ar);
			// }


		}

		// se ho uno o piu ExternalId vuol dire che l'utente e' loggato, e pertanto ha senso cercare i totali per produttore in base alle offerte
		if(isset($ExternalId) && count($groups)>0 ){
			$groups['byPublisher'] = array();
			$groups['ExternalId'] = $localId;
			$off = \OfferQuery::create()->filterByPublisher($groups['PublisherId'])
				//-- extra per modalita' singolo: solo le offerte, che dal primo vincolo sulla modalita' singolo risultano gia' una e una sola per Publisher
				->filterById($oneOfPerPublisher)
				// se non voglio quelle scadute
				// ->filterByExpiration(array("min"=>time()))->_or()->filterByExpiration(NULL)
				->find();

			foreach($off as $of){
				// svolgo una query anziche' il semplice getPrefers() ritornerebbe troppi valori superflui.
				$prefs = \PreferQuery::create()
						->filterByOfferId($of->getId())
						->filterByUserId($localId)
						// solo i nuovi concorrono alla creazione di un nuovo ordine
						->filterByState('newest')
						->find();

				// se ne ho almeno una, allora la aggiungo
				$ext = self::IdToExt($of->getPublisher());
				$groups['byPublisher'][$ext] = [];

				if($prefs->count()>0){
					$ar = array();
					$ar['Price'] = $of->getPrice();
					// $totalQt = 0;
					$pr = $prefs->toArray();
					foreach($pr as $key => $p){
					 	// $totalQt += $p['Qt'];
					 	$pr[$key]['UserId'] = self::IdToExt($p['UserId']);
					}
					$ar['prefers'] = $pr;
					// $ar['totalQt'] = $totalQt;
					// $ar['totalPrice'] = $totalQt * $ar['Price'];
					$groups['byPublisher'][$ext]['offers'][$of->getId()] = $ar;
				}
			}
			
			// ora raccolgo i constraint, in questo caso semplicemente il minPrice
			foreach ($groups['byPublisher'] as $publisherId => $value) {
				$totalByPublisher = 0 ;
				$local = self::ExtToId($publisherId);
				$g = \OfferGroupManyQuery::create()->filterByPublisherId($local)->filterByGroupOfferId(NULL)->findOne();
				if($g) $groups['byPublisher'][$publisherId]['Constraint'] = (array) json_decode( $g->getGroupConstraint() );
			}

		}



		$return['groups'] = $groups;



		// if(count($return ) == 0) $Json['response'] =false;
		if(!isset($Json['response'])){ $Json['response'] = true;}
		else {$Json['errors'] = $msg; }
		$Json['body'] = $return;
		return $Json;
		
	}




		/**
		 *
		 * @api {get} /offer group
		 * @apiName group
		 * @apiGroup Offer
		 * 
	 	 * @apiDescription Per ottenere la lista delle offerte mediante filtri, in particolare cerca solo le intersezioni dei filtri.
		 * 
		 * @apiParam {String} 		type 			metodo da chiamare (group)
		 * @apiParam {Int}	  		OfferId 		Id dell'offerta
		 * @apiParam {String} 		ExternalId 		stringa formata da uno o piu Id utenti (Id lato elgg) separati da virgola. Per ciascun utente ritorna la sua preferenza su tale offerta.
		 *
		 * @apiParamExample {url} URL-Example:
		 * 
		 * {{host}}offer?type=group&OfferId=1&ExternalId=52,37
		 * 
		 * @apiParam (Response) {Bool}				response 		false, in caso di errore
	 	 * @apiParam (Response) {String/json}		[errors] 		json contenente i messaggi di errore
	 	 * @apiParam (Response) {String/json}		[body] 			json contenente i parametri da ritornare in funzione della richiesta. Il parametro prefer impostato nel ritorno contiene eventuali preferenze che metchano gli ExternalId passati con la chiamata. I dati relativi agli Id (UserId, Publisher, ExternalId, etc.) sono ritornati come elggId.
		 * 
		 */
		public $needle_group = "OfferId, ExternalId";
		protected function group($data){

			// converto il Publisher con Id elgg in Publisher con Id User
			// $this->Ext2Id($data, 1);
			$r['response'] = false;
			
			
			$offer = \OfferQuery::create()
			  		->filterById($data->OfferId)
			  		->findOne();

			if(!$offer){
				$r['errors'] = 'L\' OfferId non corrisponde ad alcuna offerta.';
			}else{
				$of = $offer->toArray();
				$of['Publisher'] = $this->IdToExt( $of['Publisher'] );

				$prefers = array();
				$r['response'] = true;
				$ids = explode(',', $data->ExternalId);
				
				foreach($ids as $extId){
					// var_dump($extId);
					$uid = $this->ExtToId($extId);
					// var_dump($extId);
					$pref = \PreferQuery::create()
							->filterByOfferId($data->OfferId)
							->filterByUserId($uid)
							->find();
					foreach($pref as $p){
						$p = $p->toArray();
						// unset($p['Id']);
						unset($p['UserId']);
						$p['ExternalId'] = $extId;
						$prefers[] = $p;
					}
				}

				$r['body']['prefers'] = $prefers;
				$r['body']['offer'] = $of;


			}
			// var_dump(json_encode($r));

			// echo json_encode($r);

			return $r;
			
		}






	/**
	 *
	 * @api {post} /offer delete
	 * @apiName delete
	 * @apiGroup Offer
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
	 * @apiUse MyResponseOffer
	 * 
	 */
	public $needle_delete = "Publisher, Id";// State
	protected function delete($data){

		$this->Ext2Id($data);

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

				// trucco stupido per imporre l'update della data di modifica dell'offerta:
				// essendo i tags non direttamente associati alla tabella, una loro modifica non comporta
				// l'attivazione dell ON UPDATE di mysql nella tabella offers
				if(!$offer->isNew()){
					$offer->setDescription($offer->getDescription().' ');
					$offer->save();
				}
				if(isset($errors['Tag'])){ 
					$proceed = false;
				}
				
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
			  	// if($value == ''){
			  	// unset($data->{$key});
			  	// continue;
			 	// }
			 	//var_dump($data->{$key});

			 	// trasformo il separatore dei decimali
			 	if(preg_match('/^\d+\,\d*$/', $value))	$value = preg_replace('@,@', '.', $value);
			 	//var_dump("{'set'.$key}($value)");
			 	$method = 'set'.$key;
			 	if(!method_exists($offer, $method)) error_log('metodo inesistente: '.$method);
			 	$offer->{$method}($value); 
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
	 * pertanto ridefinisco il Publisher in base all' ID associato nella tabella User.
	 * @param [type] $data [description]
	 * @param [type] $noEr se impostato, gli dico di non considerare errore la non corrispondenza(vedi Search)
	 */
	protected function Ext2Id($data, $noEr =null){

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


	

	protected function hookFSave($obj){

		// faccio un controllo sulle quantita' prima del salvataggio: 
		// la minima non deve superare la massima
		$maxQt = $obj->getMaxqt();
		if(!is_null($maxQt) && $maxQt != 0){
			$minQt = $obj->getMinqt();
			if($minQt > $maxQt){
				$Json['response']= false;
				$Json['errors']['Maxqt'] = 'La quantita\' massima deve superare o eguagliare quella minima';
				echo json_encode($Json);
				exit(0);
			}
		} 

	}

}

