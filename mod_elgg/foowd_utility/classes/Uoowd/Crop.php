<?php

namespace Uoowd;

// servono: 
// il form action, preso da sticky
// la guid, presa da guid

class Crop{

	// lo uso per determinare se non ho errori nel controllo prima di effettuare il salvataggio
	public $status = true;

	/**
	 * all'inizio constrollo solo che il form abbia impostato il file da croppare 
	 *
	 * opzionalmente uso l'input sticky per creare uno sticky form
	 */
	public function __construct($empty = null){
		
		// solo se e' un utente
		elgg_gatekeeper();


		// inizio col predisporre lo sticky form, che se non esiste non crea errori
		$form = get_input('sticky');
		// register_error($form);
		
		// creo lo sticky per salvare in $_SESSION i vari messaggi
		$this->sticky = new \Uoowd\Sticky($form);
		$sticky = $this->sticky;

		if(!is_null($empty)) return;

		
		// system_message($form);

		// ora penso al file, perche' e' un parametro obbligatorio
		// controllo di avere un'immagine da salvare
		foreach($_FILES as $key => $file){
		    $error = elgg_get_friendly_upload_error($file['error']);
		    
		    // check dell'immagine
		    if(! $size =getimagesize($file['tmp_name'])){
		        $msg = 'Puoi solo inserire immagini.';
		    }else{
		        // per il momento ho un solo file, pertanto non mi serve
		        // $_FILES[$key]['extra'] = $size;
		        $file['extra'] = $size;
		    }

		    // vari errori
		    if($error) {
		        // register_error($error);
		        // attenzione all'ordine!
		        $msg = 'Errore di caricamento dell\'immagine, siamo spiacenti';
		        if(!$file['name']) $msg = 'Non hai aggiunto alcuna immagine';

		    }

		    if($msg){
		        $sticky->_setV(array('fileError'=> $msg));
		        // forward(REFERER);
		        $this->status = false;
		   }
		   // salvo il file (attualmente dovrebbe essere l'unico) in una variabile da riutilizzare nel seguito
		   $this->File = $file;
		}

		// system_message($guid.' '.$form);

	}

	/**
	 * funzione che salva l'immagine e le sue thumbnail
	 *
	 * ho bisogno della come directory per l'offerta
	 *
	 * 
	 * @return [type] [description]
	 */
	public function saveImg(){

		// recupero la classe sticky creata col costruttore
		$sticky = $this->sticky;

		// controllo sulla guid Id dell'Offerta: se non esiste non posso manco sapere dove salvare
		// $owner = get_entity($guid);
		$guid = get_input('offerGuid');
		if(!$guid){
		    $sticky->setV(array('guidError'=>'Attenzione, non posso rintracciare l\'offerta.'));
		    // forward(REFERER);
		    $this->status = false;
		    return;
		}

		// a questo punto posso impostare i parametri di salvataggio
		// $dir = str_replace('\\', '/', \Uoowd\Param::imgStore());
		// $dir .= 'User-'.elgg_get_logged_in_user_guid().'/';
		// $saveDir = $dir.$guid.'/';
		$saveDir = \Uoowd\Param::pathStore(elgg_get_logged_in_user_guid(),'offers').$guid.'/';

		// if (!file_exists($saveDir)) {
		//     mkdir($saveDir, 0777, true);
		// }

		// utile da richiamare nel caso voglia cancellare la directory per via di errori successivi
		// vedi metodo removeDir() e crop() 
		if(!isset($this->saveDir)){
			$this->saveDir = $saveDir;
		}else{
			$saveDir = $this->saveDir;
		}
		
		if(! $this->createDir($this->saveDir)) return;
		// error_log($saveDir .' in '.__FILE__);


		// recupero il file salvato col costruttore
		// NB: credo vi sia un Bug di php: $File (inteso come $_FILES) non puo' essere impostato con $sticky->setV !!!!
		$File = $this->File;

		// parto col salvataggio dell'originale nella root del folder
		// $target_file = $saveDir.$File['name'];
		$target_file = $saveDir.get_input('offerGuid').'.'.pathinfo($File['name'], PATHINFO_EXTENSION);
		// \Uoowd\Logger::addError($target_file);

		// per il metodo crop()
		if(!isset($this->target)){ 
			$this->target = $target_file;
		}else{
			$target_file = $this->target;
		}


		if (move_uploaded_file($File["tmp_name"], $target_file)) {
		    $r['message'] = "File ". basename( $target_file). " salvato con successo.";
		    $r['response'] = 'success';
		    // svuoto la directory
		    // per il momento decido di tenere un solo file per volta
		    // foreach (new DirectoryIterator($saveDir) as $fileInfo) {
		    //     if(!$fileInfo->isDot() && $fileInfo->getBasename()!=$File['name']) {
		    //         unlink($fileInfo->getPathname());
		    //     }
		    // }
		    $this->emptyDirBut($saveDir, $target_file);
		} else {
		    $r['message'] = "Purtroppo il file risulta corrotto.";
		    $r['response'] = 'error';
		    $er['fileError'] = "Purtroppo il file risulta corrotto.";
		    $er['offerGuid'] = $guid;
		    // $er['fi_le'] = json_decode($this->File);
		    $er['target'] = $target_file;
		    $sticky->setV($er);
		    $this->status = false;
		    return;
		}

		// se sono qui, la creazione del file di base e' andata bene, 
		// pertanto non mi rimane che croppare
		
		$this->crop();

		if(!isset($guid) || $guid === '') return;
		// $base = \Uoowd\Param::pathStore(elgg_get_logged_in_user_guid(),'offers');
		$base = $this->saveDir;
		error_log('elimino '.$base.' , from '.__FILE__);
		// prima di salvare tolgo eventual file temporanei presenti
		foreach (new \DirectoryIterator($base) as $fileInfo) {
			$match = preg_match('@^tmp-@', $fileInfo->getFilename());
		    if($fileInfo->isFile() && $match) {
				// error_log('cancello '.$target_file);
		        unlink($fileInfo->getPathname());
		    }
		}

		// $this->status = false;

	}

	/**
	 * se non e' fornito il crop, lo svolge in automatico
	 * @return [type] [description]
	 */
	public function crop(){


		$sticky = $this->sticky;
		$saveDir = $this->saveDir;

		// se sono qui, allora il salvataggio dell'immagine in saveImg() e' andato bene
		$target_file = $this->target;

		// elimino i limiti di memoria
		\ini_set('memory_limit', '-1');
		// recupero il file, come una string per non farmi problemi in merito al formato
		$img = imagecreatefromstring(file_get_contents($target_file)); 

		if ( !$img ) {
		    // header('Content-Type: image/png');
		    // imagepng($im);
		    // imagedestroy($im);
		    $e['mess'] = 'errore in createfromstring';
		    $e['tg'] = $target_file;
		    \Uoowd\Logger::addError($e);
		    $sticky->_setV($e);
		    $this->status = false;
		    return;
		}
		
		$w = imagesx($img);
		$h = imagesy($img);

		$crop = get_input('crop');

		// se non muovo la windows, di default i valori non sono istanziati
		if( $crop['x1']==='' ){

		    // normalizzo le dimensioni
		    // list($width, $height, $type, $attr) = $File['extra'];
		    // $w = $width;
		    // $h = $height;
		    
		    // lunghezza del ritaglio
		    $l =  min($w, $h);
		    $crop['x1'] = ($w - $l)/(2*$w);
		    $crop['x2'] = ($w + $l)/(2*$w);
		    $crop['y1'] = ($h - $l)/(2*$h);
		    $crop['y2'] = ($h + $l)/(2*$h);
		}

		$crop['w'] = $crop['x2']-$crop['x1'];
		$crop['h'] = $crop['y2']-$crop['y1'];

		// ratio
		//$crop['r'] = $crop['h']/$crop['w'];

		// salvo i dati del crop in formato json nella directory dell'immagine 
		// utile per riformarla quando si modifica un'offerta
		if(! file_put_contents($saveDir.pathinfo ( $target_file , PATHINFO_FILENAME).'-crop.json', json_encode($crop)) ){
			\Uoowd\Logger::addError('Errore nel salvataggio del file' . $target_file.'-crop.json');
		}

		// dimensioni di default thumbnail
		$imsize['small'] = 100;
		$imsize['medium'] = 250;
		$imsize['big'] = 400;


		// ora svolgo il crop e salvo le immagini
		foreach($imsize as $dir => $l){
		    // imposto la
		    $sdir = $saveDir.$dir.'/';
		    if(!$this->createDir($sdir)) return;

		    // il secondo e' per mantenere le proporzioni
		    $scaleX = $l /$crop['w'];
		    $scaleY = $scaleX * $h/$w;
		    $selectionW = $crop['w'] * $w;
		    $selectionH = $crop['h'] * $h;
		    $cropRatio = $selectionH / $selectionW;

		    $thumb = imagecreatetruecolor( $l, $l * $cropRatio);
		    
		    imagecopyresampled ( $thumb , $img , 0 , 0 , (int)($crop['x1']*$w) , (int)($crop['y1']*$h) , $l , $l*$cropRatio, (int) ($crop['w']*$w) , (int) ($crop['h']*$h) );
		    
		    // $Fname = $sdir.basename($target_file);
		    $Fname = $sdir.get_input('offerGuid').'.jpg';
		    if( strlen(get_input('offerGuid')) <= 0 ) \Uoowd\Logger::addError('Nome immagine target vuoto. Stringa di base: ' .$Fname);
		    imagejpeg( $thumb , $Fname );
		    if(!$this->emptyDirBut($sdir, basename($Fname))) return;
		}

	}


	public function createDir($dir){
		if (!file_exists($dir)) {
		    mkdir($dir, 0777, true);
		}
		// nel caso di errori di creazione, allora ritorno un errore
		if (!file_exists($dir)){
			$this->sticky->setV(array('dirError'=>'Directory: '.$dir.' , non esiste'));
			$this->status = false;
			return false;
		}

		return true;
	}

	public function emptyDirBut($dir, $baseName){

		$baseName = basename($baseName);

		// nel caso di errori di creazione, allora ritorno un errore
		if (!file_exists($dir)){
			$this->sticky->setV(array('dirError'=>'Directory: '.$dir.' , non esiste'));
			$this->status = false;
			return false;
		}

		// error_log('elimino '.$dir);
		foreach (new \DirectoryIterator($dir) as $fileInfo) {
		    if(!$fileInfo->isDot() && $fileInfo->getBasename()!=$baseName) {
		        unlink($fileInfo->getPathname());
		    }
		}

		return true;
	}

	public function removeDir($dir=null) {

		// savedir settato con metodo saveImg()
		if(is_null($dir)) $dir = $this->saveDir;

	 //    if (!file_exists($dir)) {
	 //        return true;
	 //    }
	
	 //    if (!is_dir($dir)) {
	 //        return unlink($dir);
	 //    }
	
	 //    foreach (scandir($dir) as $item) {
	 //        if ($item == '.' || $item == '..') {
	 //            continue;
	 //        }
	
	 //        if (!$this->removeDir($dir . DIRECTORY_SEPARATOR . $item)) {
	 //            return false;
	 //        }
	
	 //    }
	
	 //    return rmdir($dir);
		// }

		foreach (new \DirectoryIterator($dir) as $fileInfo) {
			// se e' dot la ignoro
			if($fileInfo->isDot()) continue;

			if($fileInfo->isDir()  && !rmdir($fileInfo->getPathname()) ){
				$this->removeDir($fileInfo->getPathname());
			} 

		    unlink($fileInfo->getPathname());

		}

		return rmdir($dir);
	}
	

}