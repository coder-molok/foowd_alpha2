<?php 

gatekeeper();
$form='foowd-avatar';
elgg_clear_sticky_form($form);

set_input('sticky','foowd-avatar');

$guid = get_input('guid');
set_input('offerGuid', $guid);

$cropData = get_input('crop');

// \Uoowd\logger::addError(\Uoowd\Param::userStore($guid).'avatar/');
// \Uoowd\logger::addError($_FILES);



$saveDir = \Uoowd\Param::userStore($guid).'avatar/';
$target = $saveDir.$guid.'.jpg';

// \Uoowd\Logger::addError($_FILES['file']);


// se non ho uploadato un nuovo file allora e' maggiore di zero
if($_FILES['file']['error']>0){
	// controllo se sono avvenuti dei cambiamenti
	$change = false;
	foreach($cropData as $value){
		if($value !== '') $change = true;
	}



	// se e' cambiato imposto il nuovo crop, atrimenti metto un default che non fa nulla
	$crop = new \Uoowd\Crop('random');
	if($change){
		// \Uoowd\Logger::addError('dentro if');
		$crop->saveDir = $saveDir;
		$crop->target = $target;
		$crop->crop();
	}

}else{
	// ora parto a controllare il file
	$crop = new \Uoowd\Crop();
	$crop->saveDir = $saveDir;
	$crop->target = $target;
	$crop->saveImg();
}




// dopo aver salvato i contenuti del post posso provare a salvare le immagini
// set_input('offerGuid', $r->Id);



if(! $crop->status){
	
	// elimino la directory delle immagini precedentemente creata
	$crop->removeDir();

	register_error('Sono avvenuti errori durante il salvataggio');

	forward(\Uoowd\Param::page()->profile);
}

// se tutto e' andato a buon fine, posso eliminare lo sticky
elgg_clear_sticky_form($form);


// ora ripulisco la directory dai files temporanei
if(!isset($guid) || $guid === '') return;
$base = \Uoowd\Param::userStore(elgg_get_logged_in_user_guid());
// error_log('elimino '.$base.' , from '.__FILE__);
// prima di salvare tolgo eventual file temporanei presenti
foreach (new \DirectoryIterator($base) as $fileInfo) {
	$match = preg_match('@^tmp-@', $fileInfo->getFilename());
    if($fileInfo->isFile() && $match) {
		// error_log('cancello '.$target_file);
        unlink($fileInfo->getPathname());
    }
}

//infine rimando alla pagina di successo
system_message('Avatar salvato con successo.');
forward(\Uoowd\Param::page()->profile);	
