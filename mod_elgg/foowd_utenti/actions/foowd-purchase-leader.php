<?php

/**
 * action richiamata quando il leader si prende in carico un ordine
 *
 * NB:
 * per testare l'invio dele mail senza l'ordine mi basta commentare 
 * 		$con->commit();
 *
 * in foowd-purchase-leader funzione create, intorno alla riga 170
 */


/**
 * funzione di comodita', del tutto superflua.
 * La utilizzo per scrive nell'error log e controllare i dati.
 * 
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function pri($str){
	\Uoowd\Logger::addError($str);
}


$j['response'] = false;

$orderError = 'Errore di aggiornamento dell\'ordine, ci scusiamo per il disguido.';

// I processi da svolgere sono 2:
// 
// 1 - detrarre da ciascun utente le sue preferenze eventualmente rimandando messaggi d'errore, email e notifiche
// 2 - in caso di buon fine far partire l'ordine (o meglio: email a utente capogruppo e a produttore)


//// DATI POST
/// 	OfferId
/// 	LeaderId (il suo id)
/// 	prefersList (lista di id delle preferenze)
// $publisher = get_user_by_username( $_POST['publisher'] );

// pri($_POST);
$leader = get_user( $_POST['LeaderId'] );


// id offerta
$oId = $_POST['OfferId'];


// // controlli generici
// if(!$publisher){
// 	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
// 	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il publisher');
// 	forward(REFERER);
// }

if(!$leader){
	$txt = 'Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.';
	register_error($txt);
	echo $txt;
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il manager');
	forward(REFERER);
}

if(!$oId){
	$txt = 'Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.';
	register_error($txt);
	echo $txt;
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca l\'offerid');
	forward(REFERER);
}


// le preferenze possono essere o una lista di id, o una lista di Oggetti-preferenza
$prefersList = array();

if( is_array($_POST['prefersList']) ){
	foreach($_POST['prefersList'] as $p){
		$prefersList[] = $p['preferid'];
	}
	$prefersList = implode( $prefersList, ',' );
}else{
	$prefersList = $_POST['prefersList'];
}



// creo ordine offerid leaderid prefersList (id preferenze)
// Purchase create

$data['type'] = 'create'; // metodo commonOffers di ApiUser
$data['OfferId'] = $oId;
$data['LeaderId'] = $leader->guid;
$data['prefersList'] = $prefersList;
$r = \Uoowd\API::Request('purchase','POST', $data);

if(!$r->response){
	echo json_encode($r);
	register_error($orderError);
	\Uoowd\Logger::addError('Request');
	\Uoowd\Logger::addError($data);
	\Uoowd\Logger::addError('Response');
	\Uoowd\Logger::addError($r);

	forward(REFERER);
}

// NB: nel caso manchino dati relativi a preferenze e offerta, posso ritornarli nella chiamata appena svolta

// se e' andato a buon fine, allora la risposta ha un body
$b = $r->body;
$offer = $b->offer;


// raccolgo i dati per l'invio delle mail a leader e utenti
$offerName = $offer->Name;
$offerId = $offer->Id;
$offerPrice = $offer->Price;

// lista delle preferenze con dettagli
$prefers = $r->body->prefers;
// publisher dell'offerta
$publisher = get_user($offer->Publisher);


// // *********************************************************************************************/
// // Elaboro la data, impostando 24 ore arrotondate ai primi 30 minuti successivi (per via del crontab)
// $now = new DateTime();
// $purch = new \Uoowd\FoowdPurchase();
// $deltaT = $purch->trigger;
// $now->add(new DateInterval('PT'.$deltaT.'S'));
// // giorno della settimana, partendo da zero
// $D = (int) $now->format('w');
// // mese dell'anno partendo da zero
// $M = (int) $now->format('m');
// // secondi dell'orologio
// $s = (int) $now->format('s');
// // minuti dell'orologio
// $m = (int) $now->format('i');

// $dateLimit = sprintf("%s %s (domani)", $now->format('d'), \Uoowd\FoowdCron::$mesi[$M] );

// // arrotondo ai primi n minuti successivi, ovvero l'orario a cui effettivamente viene eseguito il crontab
// $round = $purch->cronTab ;
// $seconds = $m * 60 + $s ;
// $nearest = ceil($seconds/$round) * $round;
// $remain = $nearest - $seconds;
// $now->add(new DateInterval('PT'.$remain.'S'));
// $timeLimit = $now->format('H:i');
// // echo $timeLimit;
// \Uoowd\Logger::addError($dateLimit.$timeLimit);
// //********* Fine elaborazione Data ******/


// lavoro sui dati ritornati dalla API

// classe dei messaggi
$messenger = new \Uoowd\PurchaseMessageEmail();

$totalQt = 0 ;

// array contenente i dati utili per la mail del leader
$leaderAr = array();
$leader['ofDetail'] = array();

// Mail agli Utenti
foreach($prefers as $pr){
	$us = get_entity($pr->UserId);
	if(!$us){
		pri("Errore nel recuperare l'utente mediante la Preferenza: ". json_encode($pr));
		continue;
	}

	// che sia il leader o meno, la quantita' totale va incrementata
	$totalQt += $pr->Qt;

	// dettagli per riepilogo al leader
	$v = array(
		'qt' => $pr->Qt,
		'price' => $offerPrice,
		'singleUsr' => $us->username,
	);
	$leaderAr['ofDetail'][] = $v;

	// se e' il leader, allora raccolgo i dati utili, ma non gli mando la mail da questo loop
	if($pr->UserId === $leader->guid){
		// leader preference
		$leaderAr['qt'] = $pr->Qt;
		continue;
	}

	// array dei parametri
	$data = array();
	$data['singleUsr'] = $us->username;
	$data['mngrUsr'] = $leader->username;
	$data['mngrEmail'] = $leader->email;
	$data['ofName'] = $offerName ; 
	$data['ofId'] = $offerId ; 
	$data['qt'] = $pr->Qt; 
	$data['price'] = $offerPrice; 
	// $data['timeLimit'] = $timeLimit ; 
	// $data['dateLimit'] = $dateLimit ;

	$msg = $messenger->userOrderSingleMsg($data);

	$emailTo = $us->email;
	$from = 'Foowd Site';
	$subject = 'Un tuo amico ha preso in carico un\'offerta che segui';
	elgg_send_email($from, $emailTo, $subject, $msg->altMsg, array('htmlBody'=>$msg->htmlMsg) );
}


// Mail al leader
// il leader so gia' chi e' e sono gia' sicuro che sia un utente valido (vedi inizio script)
$leaderAr['mngrUsr'] = $leader->username;
$leaderAr['ofName'] = $offerName;
$leaderAr['pubName'] = $publisher->username;
$leaderAr['pubEmail'] = $publisher->email;
$leaderAr['totqt'] = $totalQt;
$leaderAr['ofId'] = $offerId;
$leaderAr['price'] = $offerPrice;
// $leaderAr['timeLimit'] = $timeLimit;
// $leaderAr['dateLimit'] = $dateLimit;


// \Uoowd\Logger::addError($leaderAr);
$msg = $messenger::managerOrderSingleMsg($leaderAr);
$emailTo = $leader->email;
$from = 'Foowd Site';
$subject = 'Offerta "'.$offerName.'" presa in carico';
elgg_send_email($from, $emailTo, $subject, $msg->altMsg, array('htmlBody'=>$msg->htmlMsg) );

$j['response'] = true;

echo json_encode($j);

