<?php

/**
 * action richiamata quando il leader si prende in carico un ordine
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
$messenger = new \Uoowd\MessageEmail();

// I processi da svolgere sono 2:
// 
// 1 - detrarre da ciascun utente le sue preferenze eventualmente rimandando messaggi d'errore, email e notifiche
// 2 - in caso di buon fine far partire l'ordine (o meglio: email a utente capogruppo e a produttore)


//// DATI POST
/// 	offerId
/// 	leader (il suo id)
/// 	publisher (dell'offerta)
/// 	prefers (lista di id delle preferenze)

// $publisher = get_user_by_username( $_POST['publisher'] );

pri($_POST);
$leader = get_user( $_POST['LeaderId'] );
// $offerId = $_POST['OfferId'];

// id offerta
$oId = $_POST['OfferId'];


// // controlli generici
// if(!$publisher){
// 	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
// 	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il publisher');
// 	forward(REFERER);
// }

if(!$leader){
	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il manager');
	forward(REFERER);
}

if(!$oId){
	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
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

$prefers = $r->body->prefers;

// *********************************************************************************************/
// Elaboro la data, impostando 24 ore arrotondate ai primi 30 minuti successivi (per via del crontab)
$now = new DateTime();
$purch = new \Uoowd\FoowdPurchase();
$deltaT = $purch->trigger;
$now->add(new DateInterval('PT'.$deltaT.'S'));
// giorno della settimana, partendo da zero
$D = (int) $now->format('w');
// mese dell'anno partendo da zero
$M = (int) $now->format('m');
// secondi dell'orologio
$s = (int) $now->format('s');
// minuti dell'orologio
$m = (int) $now->format('i');

$dateLimit = sprintf("%s %s (domani)", $now->format('d'), \Uoowd\FoowdCron::$mesi[$M] );
echo $dateLimit;

// arrotondo ai primi n minuti successivi, ovvero l'orario a cui effettivamente viene eseguito il crontab
$round = $purch->cronTab ;
$seconds = $m * 60 + $s ;
$nearest = ceil($seconds/$round) * $round;
$remain = $nearest - $seconds;
$now->add(new DateInterval('PT'.$remain.'S'));
$timeLimit = $now->format('H:i');
echo $timeLimit;
//********* Fine elaborazione Data ******/


// lavoro sui dati ritornati dalla API

$totalQt = 0 ;

// Mail agli Utenti
foreach($prefers as $pr){
	$us = get_entity($pr->UserId);
	if(!$us){
		pri("Errore nel recuperare l'utente mediante la Preferenza: ". json_encode($pr));
		continue;
	}

	$totalQt += $pr->Qt;

	// array dei parametri
	$data = array();
	$data['singleUsr'] = $us->username;
	$data['mngrUsr'] = $leader->username;
	$data['mngrEmail'] = $leader->email;
	$data['ofName'] = $offerName ; 
	$data['ofId'] = $offerId ; 
	$data['qt'] = $pr->Qt; 
	$data['price'] = $offerPrice; 
	$data['timeLimit'] = $timeLimit ; 
	$data['dateLimit'] = $dateLimit ;

	$msg = $messenger->userOrderFirstMsg($data);

	$emailTo = $us->email;
	$from = 'Foowd Site';
	$subject = 'Un tuo amico ha preso in carico un\'offerta che segui';
	elgg_send_email($from, $emailTo, $subject, $msg->altMsg, array('htmlBody'=>$msg->htmlMsg) );
}


// Mail al leader
// il leader so gia' chi e' e sono gia' sicuro che sia un utente valido (vedi inizio script)

$ar = array();
$ar['mngrUsr'] = $leader->username;
$ar['ofName'] = $offerName;
// $ar['pubName'] = 'Azienza Agricola Rnd';
// $ar['pubEmail'] = 'via@rnd.com';
$ar['ofId'] = $offerId;
$ar['qt'] = 22;
$ar['price'] = $offerPrice;
$ar['tqt'] = $totalQt;
$ar['timeLimit'] = $timeLimit;
$ar['dateLimit'] = $dateLimit;


$msg = $messenger::managerOrderFirstMsg($ar);
$emailTo = $leader->email;
$from = 'Foowd Site';
$subject = 'Offerta "'.$offerName.'" presa in carico';
elgg_send_email($from, $emailTo, $subject, $msg->altMsg, array('htmlBody'=>$ntf->msg->htmlMsg) );

$j['response'] = true;

echo json_encode($j);