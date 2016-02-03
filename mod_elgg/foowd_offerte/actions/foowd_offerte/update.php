<?php

/**
 * action richiamata alla modifica di una offerta
 *
 * @todo implementare controlli su campi modificabili, e giro di email
 * 
 */

gatekeeper();

$form = \Uoowd\Param::pid().'/update';

// set sticky: avviso il sistema che gli input di questo form sono sticky
elgg_make_sticky_form($form);

// richiamo la classe che gestisce il form
$f = new \Foowd\Action\FormAdd();

// Preparo il Tag passato mediante checkbox
$tag = array();
foreach(get_input('Tag') as $val) if($val!='0') array_push($tag, $val);

// NB: set input imposta il valore all'input del form, ma non cambia il corrispettivo in $_SESSION!!
// pertanto se non faccio altro, il valore di ritorno di TAG rimane l'array che avevo passato
// e non la stringa che imposto di seguito
set_input('Tag', implode( ' , ', $tag)  );
$_SESSION['sticky_forms'][$form]['str']=$_SESSION['sticky_forms'][$form]['Tag'];

$data = $f->manageForm($form);

// imposto la data
// $data['Modified'] =date('Y-m-d H:i:s');
$data['Publisher']=elgg_get_logged_in_user_guid();


// se non ho uploadato un nuovo file allora e' maggiore di zero
if($_FILES['file']['error']>0){
	// controllo se sono avvenuti dei cambiamenti
	$crop = get_input('crop');
	$change = false;
	foreach($crop as $value){
		if($value !== '') $change = true;
	}

	// se e' cambiato imposto il nuovo crop, atrimenti metto un default che non fa nulla
	$crop = new \Uoowd\Crop('random');
	if($change){
		$crop->saveDir = \Uoowd\Param::pathStore(get_input('guid'),'offers').get_input('Id').'/';
		$crop->target = $crop->saveDir . get_input('fileBasename');
	}

}else{
	// ora parto a controllare il file
	$crop = new \Uoowd\Crop();
}

// forzo ugualmente il crop dell'immagine: lo eseguo indipendentemente dalle preferenze
if($change){
	$crop->crop();
}else{
	$crop->saveImg();
}

// $_SESSION['sticky_forms'][$form]['pre-file']=$crop;

if(!$f->status || !$crop->status) forward(REFERER);


// recupero alcuni parametri, come i metatag ivi associati
$ofCron = new \Uoowd\FoowdOffer();
$ofId = $data['Id'];
// raccolgo eventuale oggetto elgg di modifica
$elggOfr = elgg_get_entities_from_metadata(
	array( 'metadata_names'=>array($ofCron->checkEditMetatag), 'metadata_values'=>array($ofId) )
);


// svolgo la chiamata
$r = $ofCron->offerPrefersCall(get_input('Id'));
if(!$r->response) return;
$body = $r->body;


// vecchia offerta
$oldOffer = $body[0]->offer;

$diffs = $ofCron->findFieldDiffs((array) $oldOffer, (array) $data);
$editableByDiff = $diffs['editableByDiff'];
$inputDiffs = $diffs['inputDiffs'];


// \Uoowd\Logger::addError($diffs);

// Se il conto non e' zero e i campi modificati non sono modificabili a priori (ad esempio i Tag)
if(count($body) > 0 && !$editableByDiff ) goto __notEditable;

////////////////////// MODIFICO OFFERTA ///////////////////////////////////////////////////////////////////
// \Uoowd\Logger::addError('posso modificare senza problemi');

// se tutto va a buon fine, proseguo con le API esterne
$data['type']='update';
// \Uoowd\Logger::addError($data);
$r = \Uoowd\API::offerPost($data);

if($r->response){

	// posso cancellare l'oggetto contenente le modifiche, in quanto applicate
	if(count($elggOfr) == 1 ) $elggOfr[0]->delete(true);

	// dico al sistema di scartare gli input di questo form
	// elgg_clear_sticky_form('foowd_offerte/add');
	// $input = (array) $r->body[0];
	

	// dopo aver salvato i contenuti del post posso provare a salvare le immagini
	set_input('offerGuid', $r->Id);

	// se c'e' stato il cambiamento senza l'upload svolgo semplicemente il crop, 
	// altri procedo col normale salvataggio
	// if($change){
	// 	$crop->crop();
	// }else{
	// 	$crop->saveImg();
	// }


	//NB: tutta questa parte dell'insuccesso del crop non la svolgo
	//		in quanto almeno un'immagine di default ci vuole
	//		al limite devo implementare un sistema di log
	//		
	//		in ogni caso sicuramente non devo eliminare l'offerta per un errore nell'immagine
	
	// se il crop non e' avvenuto, allora elimino l'articolo salvato con le API
	// recupero l'id dell'offerta: mi serve per creare la directory in cui salvare
	// if(! $crop->status){
		
	// 	// elimino la directory delle immagini precedentemente creata
	// 	// in realta' sull'update non le rimuovo
	// 	// $crop->removeDir();

	// 	$data2['Publisher']=elgg_get_logged_in_user_guid();
	// 	$data2['Id']=(int) $r->Id;
	// 	$data2['type']='delete';

	// 	$r2 = \Uoowd\API::Request('offer','POST', $data2);
	// 	if($r2->response){
	// 		// system_message(elgg_echo("eliminato il post ".$data2['Id']));
	// 	}else{

	// 		// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
	// 		if(! $str = \Uoowd\Param::dbg()){ 
	// 			$str = "errore nell'eliminazione di offerta numero ".$data2['Id'];
	// 		}
	// 		register_error($str);
	// 	}

	// 	forward(REFERER);
	// }


	// sempre cancellare dopo un successo,
	// in modo da forzare, secondo il mio algoritmo, il ricaricamento dei dati
	elgg_clear_sticky_form($form);
	system_message(elgg_echo("Offerta ".$data['Id']." aggiornata!"));
	// forward('foowd_offerte/success');
	forward(\Uoowd\Param::page()->all);
}else{
	$_SESSION['sticky_forms'][$form]['apiError']=$r;

	// nel caso non stia usando il debug impostato nel plugin, stampo un messaggio normale
	if(! $str = \Uoowd\Param::dbg()){ 
		$str = 'Impossibile caricare l\'offerta';
	}
	register_error(elgg_echo($str));
}
/////////////////////////////////////////////////////// Fine Update ///////////////////////////////////



__notEditable:

// se sono qui, vuol dire che $body non e' vuoto
$prefs = $ofCron->prefersByState($body);
// rendo disponibili gli array $newest e $pending
extract($prefs);

// \Uoowd\Logger::addError($body);

// se fa parte anche e solo di un ordine, allora non e' modificabile
if(count($pending) > 0 ){	

	register_error('Questa offerta non e\' al momento modificabile perché coinvolta in ordini pendenti.<br/> Contatta lo staff per ulteriori informazioni.' );

}
// se ha solo preferenze newest, allora entro un'ora e' modificabile
else{

	// controllo se ho un oggetto elgg in cui avevo caricato l'offerta in pre-salvataggio, 
	// altrimenti creo un nuovo oggetto in cui salvare temporaneamente i dati (una specie di river)

	if(count($elggOfr) > 1 ) \Uoowd\Logger::addError("Modifica offerta Id $ofId : ci sono troppi oggetti elgg associati ad essa");
	if(count($elggOfr) == 1 ) $elggOfr = $elggOfr[0];
	// creo un nuovo oggetto
	if(count($elggOfr) <= 0 ){
		\Uoowd\Logger::addError('Modifica offerta Id $ofId : non esiste ancora l\'ElggObject');
		$elggOfr = new ElggObject();
		$elggOfr->{$ofCron->checkEditMetatag} = $ofId;
	}
	// attualizzo i valori
	$elggOfr->description = json_encode($inputDiffs);
	$elggOfr->save();

	// \Uoowd\Logger::addError($inputDiffs);
	// error_log($elggOfr->description);


	// tra un 1 e controllo di crontab ogni 30 minuti
	$time = $ofCron->getEstimateExpiration($elggOfr);
	$time = $time['time'];
	system_message("Offerta aggiornata.<br/>Puoi fare altre modifiche fino alle ore $time. Dopo di che verr&agrave; inviata comunicazione delle modifiche agli utenti interessati.");

}


// ritorno alla pagina di modifica dell'offerta
forward(REFERER);