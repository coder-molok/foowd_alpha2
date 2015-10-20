
<?php

// funzione di comodita', del tutto superflua. 
// La utilizzo per scrive nell'error log e controllare i dati
function pri($str){
	\Uoowd\Logger::addError($str);
}


$j['response'] = false;


$orderError = 'Errore di aggiornamento dell\'ordine, ci scusiamo per il disguido.';

// username, offerName, manageruserName
$userMsg ='Salve %s,

il tuo amico %s si e\' preso carico di gestire la spedizione relativa all\'offerta
    
    %s

per la quale avevi espresso gradimento.
In seguito a questa operazione il sistema ha automaticamente azzerato le tue preferenze per quest\'offerta, ma puoi tranquillamente esprimerne di nuove.
Hai espresso:

preferenze: %s 
a:          %s &euro; Cad.
--------------------------
Totale:     %s &euro;

Per maggiori dettagli devi contattare %s all\'indirizzo %s .

Cordialmente,
Foowd
';

// publisherusername, offername, managerusername
$publisherMsg ='Salve %s,

l\'utente %s ha deciso di prendere in carico l\'ordinazione relativa all\'offerta 

    %s

secondo quanto specificato:

Quote totali    :  %s
Prezzo per quota:  %s &euro; Cad.
----------------  ------
Totale:            %s &euro;


Per maggiori dettagli deve contattare %s all\'indirizzo %s .

Cordialmente,
Foowd
';




// managerusername, offername, userlists, publishername
$managerMsg = 'Salve %s, 

l\'offerta %s e\' stata presa in carico con successo. 

Di seguito riepiloghiamo i dettagli:

%s

Per completare le procedure di pagamento e riscossione deve contattare il promotore dell\'offerta all\'indirizzo

   %s

Cordialmente,
Foowd
';

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
		$tot = $pref->Qt * $offerPrice;
		$msg = vsprintf($userMsg, array($ent->username, $manager->username , $offerName, $pref->Qt, $offerPrice, $tot, $manager->username, $manager->email) );
		// eventuali parametri da restaurare
		$data['Qt'] = $pref->Qt;
		$restore[] = $data;
		// dati per la notifica in caso di successo
		$ntf = new stdClass();
		$ntf->emailTo = $ent->email;
		$ntf->msg = $msg;
		$notify[] = $ntf;
		// salvo per il riepilogo del manager
		$manager_recap[] = "\t - quantita': ".$pref->Qt." X prezzo(euro/cad): $offerPrice \t= $tot \t, utente: ".$ent->username." \n";	

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
	elgg_send_email('Foowd Site', $ntf->emailTo, '', $ntf->msg, array());
}

// SE TUTTO E" ANDATO BENE MANDO LA MAIL A PRODUTTORE E MANAGER
// Mail To Publisher
$tot = $totalQt * $offerPrice;
$msg = vsprintf($publisherMsg, array($publisher->username, $manager->username, $offerName, $totalQt, $offerPrice, $tot, $manager->username, $manager->email) );
// pri($msg);
elgg_send_email('Foowd Site',$publisher->email,'Offerta: ordine', $msg, array());


// Mail To Manager
$manager_recap = implode($manager_recap, "\n");
$msg = vsprintf($managerMsg, array($manager->username, $offerName, $manager_recap, $publisher->email) );
// pri($msg);
elgg_send_email('Foowd Site',$manager->email,'Offerta presa in carico', $msg, array());



echo json_encode($j);