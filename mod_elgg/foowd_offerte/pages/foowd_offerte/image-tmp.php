<?php


// caso chiamata javascript
// if (!elgg_is_xhr()) {
//     register_error('Sorry, Ajax only!');
//     forward();
// }

elgg_gatekeeper();

// $form = 'foowd_utility/uploader';//get_input('sticky');
// $sticky = new \Uoowd\Sticky($form);

// guid dello USER
$guid = get_input('guid');
// id offerta: lo stesso di quello foowd_api
$id = get_input('Id');

// il post non e' vuoto, get contiene solo la uri
// $sticky->setV(array('get'=>$_GET, 'post'=>$_POST, 'files'=>$_FILES, 'prova'=>'provo'));
// solo adesso che sono in fase di test
// $sticky->setV(array('dir'=>$saveDir, 'guid'=>$guid));
//$dir = str_replace('\\', '/', \Uoowd\Param::imgStore());

$saveDir = \Uoowd\Param::pathStore($guid,'offers').$id.'/';
// error_log(print_r($saveDir, true));

if (!file_exists($saveDir)) {
    if(!mkdir($saveDir, 0777, true)) \Uoowd\Logger::addError('Impossibile creare: '.$saveDir);
}


// // parto col salvataggio
// $r = array('guid'=>$guid);
// echo json_encode($r);
// return;

// salvo gli originali
foreach($_FILES as $file){
	$prefix = 'tmp-';
	$fname = $prefix . $file['name'];
	$target_file = $saveDir . $fname;

	// prima di salvare tolgo eventual file temporanei presenti
	foreach (new DirectoryIterator($saveDir) as $fileInfo) {
		$match = preg_match('@^'.$prefix.'@', $fileInfo->getFilename());
	    if($fileInfo->isFile() && $match) {
			// error_log('cancello '.$target_file);
	        unlink($fileInfo->getPathname());
	    }
	}
	
	if (move_uploaded_file($file["tmp_name"], $target_file)) {

		// per il momento decido di tenere un solo file per volta

        $r['message'] = "File ". basename( $target_file). " salvato con successo.";
        $r['response'] = true;

        $r['preSrc'] = 'data:'.$file['type'].';base64,';
        $r['src'] = base64_encode(file_get_contents($target_file));

        $r = array_merge($r, $file);
   	} else {
        $r['message'] = "Purtroppo il file risulta corrotto.";
        $r['response'] = false;
   	}
   	

	// $sticky->setV($r);
}
echo json_encode($r);
// echo json_encode($sticky->toArray());
