
<?php

// funzione di comodita', del tutto superflua. 
// La utilizzo per scrive nell'error log e controllare i dati
function pri($str){
	\Uoowd\Logger::addError($str);
}


$j['response'] = false;


$orderError = 'Errore di aggiornamento dell\'ordine, ci scusiamo per il disguido.';
$messenger = new \Uoowd\MessageEmail();


// I processi da svolgere sono 2:
// 
// 1 - detrarre da ciascun utente le sue preferenze eventualmente rimandando messaggi d'errore, email e notifiche
// 2 - in caso di buon fine far partire l'ordine (o meglio: email a utente capogruppo e a produttore)


$publisher = get_user_by_username( $_POST['publisher'] );
$manager = get_user_by_username( $_POST['groupmanager'] );
// id offerta
$oId = $_POST['offerid'];


// controlli generici
if(!$publisher){
	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il publisher');
	forward(REFERER);
}

if(!$manager){
	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il manager');
	forward(REFERER);
}

if(!$oId){
	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca l\'offerid');
	forward(REFERER);
}


// faccio un controllo perche' gli utenti potrebbero aver modificato le preferenze nel lasso di tempo che intercorre
// tra quando il manager apre la pagina e quando decide di prendersi carico dell'ordine
// NB: continuo a fare richieste http, ma per il momento essendo tutto sullo stesso host e lato server non ho problemi di prestazioni dovute alla connessione

// array che conterra' tutti gli utenti con le loro preferenze
$farm = array();

$friends = array();
foreach($_POST['prefers'] as $p){
	$ent = get_user_by_username($p['username']);
	$guid = $ent->guid;
	$tmp = new stdClass();
	$tmp->ent = $ent;
	// aggiungo l'utente come proprieta' della stdclass
	$farm[$guid] = $tmp;
	$friends[] = $guid;
}
$friendsList = implode($friends, ',');


// con questo trovo le offerte comuni tra l'utente e i suoi amici
$data['type'] = 'group'; // metodo commonOffers di ApiUser
$data['OfferId'] = $oId;
$data['ExternalId'] = $friendsList;
$query = '';
foreach ($data as $key => $value) {
	$query .= "$key=$value&";
}
$query = trim($query, '&');
$r = \Uoowd\API::Request('offer?'.$query,'GET');
if(!$r->response){
	register_error($orderError);
	\Uoowd\Logger::addError('Errore di ritorno delle API nella query: offer?'. $query);
	forward(REFERER);
}

$body = $r->body;

// imposto tutti i parametri attuali (quelli appena ritornati)

$totalQt = 0;
$minQt = $body->offer->Minqt;
$offerName = $body->offer->Name;
$offerPrice = $body->offer->Price;

// variabile in cui scrivo il riepilogo per il manager del gruppo:
// contiene i dati di preferenza di ciascun utente
$manager_recap = array();

foreach ($body->prefers as $v) {
	$farm[$v->ExternalId]->prefer = $v;
	$totalQt += $v->Qt;
	// pri($v);
}

if($totalQt < $minQt){
	register_error('Errore nel conteggio delle quote:<br/> il totale di quelle selezionate non supera la quantia\' minima richiesta nell\'offerta');
	forward(REFERER);
}

// visto che sono arrivato qui vuol dire che tutti i controlli precedentemente svolti sono andati a buon fine, 
// pertanto posso finalmente procedere alla decurtazione della quota e al successivo invio delle mail

// parametro di controllo: uscito dal loop dovra' essere uguale alla totalQt appena calcolata
// altrimenti vuol dire che almeno una preferenza non e' stata modificata con successo
$testTotalQt = 0;
$data['type'] = 'create';

// array per ripristinare tutte le offerte in caso di errori
$restore=array();
// array per l'invio delle notifiche in caso di completamento corretto
$notify = array();

foreach($farm as $s){

	$ent = $s->ent;
	$pref = $s->prefer;

	$data['OfferId'] = $pref->OfferId;
	$data['ExternalId'] = $ent->guid;
	// allo stato attuale Qt e' una quantita' intera, pertanto non vi sono problemi di troncamento
	$data['Qt'] = -1 * intval($pref->Qt);
	$r = \Uoowd\API::Request('prefer','POST', $data);
	// pri('eseguo API');

	if($r->response){
		// email a utente
		$ar = array();
		$ar['singleUsr'] = $ent->username;
		$ar['mngrUsr'] = $manager->username;
		$ar['mngrEmail'] = $manager->email;
		$ar['ofName'] = $offerName;
		$ar['ofId'] = $oId;
		$ar['qt'] = $pref->Qt;
		$ar['price'] = $offerPrice;
		$msg = $messenger->userOrderMsg( $ar );
		// eventuali parametri da restaurare
		$data['Qt'] = $pref->Qt;
		$restore[] = $data;
		// dati per la notifica in caso di successo
		$ntf = new stdClass();
		$ntf->emailTo = $ent->email;
		$ntf->msg = $msg;
		$notify[] = $ntf;
		// salvo per il riepilogo del manager
		$v = array();
		$v['qt'] = $pref->Qt;
		$v['price'] =  $offerPrice;
		$v['singleUsr'] = $ent->username;
		$row = $messenger->managerSingleOrderMsg($v);
		$manager_recap_alt[] = $row->altMsg;
		$manager_recap_html[] = $row->htmlMsg;

		// incremento per il controllo
		$testTotalQt += $pref->Qt;
	}
	else{
		// se il responso e' negativo allora non e' nemmeno riuscito a svolgere il salvataggio, 
		// pertanto non ho nulla da restaurare nella preferenza
		\Uoowd\Logger::addError("E' avvenuto un errore sulla preferenza di cui i dati sottostanti:");
		\Uoowd\Logger::addError($pref);
	}
}

// pri($restore);
// pri($notify);

if($testTotalQt !== $totalQt){
	register_error("E' avvenuto un errore mentre l'ordine veniva processato. Abbiamo annullato ogni operatione. <br/>\n Ci scusiamo per il disguido.");
	\Uoowd\Logger::addError('Avvenuto errore durante l\'ordinazione. Dati passati alla action:');
	\Uoowd\Logger::addError($_POST);
	// ricostruisco le preferenze che avevo inizializzato poiche' l'ordine non puo' essere processato
	foreach ($restore as $data) {
		// pri('restore');
		// pri($data);
		$r = \Uoowd\API::Request('prefer','POST', $data);
		if(!$r->response){
			\Uoowd\Logger::addError('Incontrato errore nel ripristino della preferenza:');
			\Uoowd\Logger::addError($data);
		}else{
			// pri('Preferenza ripristinata');
		}
	}
	forward(REFERER);
}

// sino a qui tutto e' andato a buon fine, pertanto  provvedo a mandare tutte le notifiche a utenti, offerente e manager 

$j['response'] = true;


foreach($notify as $ntf){
	elgg_send_email('Foowd Site', $ntf->emailTo, 'Qualcuno ha preso in carico una tua offerta', $ntf->msg->altMsg, array('htmlBody'=>$ntf->msg->htmlMsg));
}

// SE TUTTO E" ANDATO BENE MANDO LA MAIL A PRODUTTORE E MANAGER
// Mail To Publisher
$ar = array();
$ar['pubUsr'] = $publisher->username;
$ar['mngrUsr'] = $manager->username;
$ar['mngrMail'] = $manager->email;
$ar['ofName'] = $offerName;
$ar['ofId'] = $oId;
$ar['qt'] = $totalQt;
$ar['price'] = $offerPrice;
$msg = $messenger->publisherOrderMsg ($ar);
// pri($msg);
elgg_send_email('Foowd Site',$publisher->email,'Offerta: ordine', $msg->altMsg, array('htmlBody'=>$msg->htmlMsg));


// Mail To Manager
$ar = array();
$ar['mngrUsr'] = $manager->username;
$ar['pubEmail'] = $publisher->email;
$ar['ofName'] = $offerName;
$ar['ofId'] = $oId;

$ar['detailsRowAlt'] = implode($manager_recap_alt, "\n");
$ar['detailsRowHtml'] = implode($manager_recap_html, "\n");

$msg = $messenger->managerOrderMsg( $ar );
// pri($msg);
elgg_send_email('Foowd Site',$manager->email,'Offerta presa in carico', $msg->htmlAlt, array('htmlBody'=>$msg->htmlMsg));



echo json_encode($j);