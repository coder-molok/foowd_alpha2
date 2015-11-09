
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

$publisher = get_user_by_username( $_POST['publisher'] );
$leader = get_user_by_username( $_POST['leader'] );
$offerId = $_POST['offerid'];

// id offerta
$oId = $_POST['offerid'];


// controlli generici
if(!$publisher){
	register_error('Impossibile svolgere l\'operazione richiesta, ci scusiamo per il disagio.');
	\Uoowd\Logger::addError('Errore durante la creazione dell\'ordine. Manca il publisher');
	forward(REFERER);
}

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




$prefersList = array();

if( is_array($_POST['prefers']) ){
	foreach($_POST['prefers'] as $p){
		$prefersList[] = $p['preferid'];
	}
	$prefersList = implode( $prefersList, ',' );
}else{
	$prefersList = $_POST['prefers'];
}



// creo ordine offerid leaderid prefersList (id preferenze)
// Purchase create

$data['type'] = 'create'; // metodo commonOffers di ApiUser
$data['OfferId'] = $offerId;
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

// lavoro sui dati ritornati dalla API

// Mail agli Utenti
foreach($prefers as $pr){
	$us = get_entity($pr->UserId);
	if(!$us){
		pri("Errore nel recuperare l'utente mediante la Preferenza: ". json_encode($pr));
		continue;
	}

	$msg = "(messaggio di test, non quello definitivo)

		Gentile %s ,
		sei parte di un gruppo con invio merce a breve! 
		Stai ordinando: \n
		%s ,\n
		in quantia' %s a %s Cad. = %s euro.\n
		Collegati se intendi modificare le quantità in ordine.
	";

	$qt = $pr->Qt;
	$tot = number_format($qt*$offerPrice, 2, '.', ' ');
	$username = $us->username;

	$msg = sprintf($msg , $username, $offerName, $qt, $offerPrice, $tot);

	$emailTo = $us->email;
	$from = 'Foowd Site';
	$subject = 'Un tuo amico ha preso in carico un\'offerta che segui';
	elgg_send_email($from, $emailTo, $subject, $msg, array(/*'htmlBody'=>$ntf->msg->htmlMsg*/) );
}


// Mail al leader
// il leader so gia' chi e' e sono gia' sicuro che sia un utente valido (vedi inizio script)

$msg = "(messaggio di test, non quello definitivo)
	Gentile %s, \n
	grazie della disponibilità a ricevere. \n

	Gli altri membri del gruppo stanno confermando i propri acquisti: \n

	      entro 24 ore riceverai copia dell'ordine inviato al produttore";


$msg = sprintf($msg, $leader->username);
$emailTo = $leader->email;
$from = 'Foowd Site';
$subject = 'Offerta "'.$offerName.'" presa in carico';
elgg_send_email($from, $emailTo, $subject, $msg, array(/*'htmlBody'=>$ntf->msg->htmlMsg*/) );

$j['response'] = true;

echo json_encode($j);