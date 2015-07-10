<?php

namespace Uoowd;

// servono: 
// il form action, preso da sticky
// la guid, presa da guid

class FoowdCrop{

	// lo uso per determinare se non ho errori nel controllo prima di effettuare il salvataggio
	public $status = true;


	/**
	 * salvo la prima immagine, impostando la directory come default per eventuali crop
	 *
	 * imposto globalmente:
	 * saveDir, che e' la directory di partenza
	 * File, ovvero il file immagine 
	 * guid, la guid dell'utente
	 * target, il nome dell'immagine che viene salvata, path incluso
	 * sticky, classe sticky form
	 * baseFile , il nome base: file1, file2, etch.
	 * cropSize, i parametri di ogni singolo crop
	 * 
	 * 
	 * @param  [type] $directory rispetto a \Uoowd\Param::imgStore()
	 * @return [type]            [description]
	 */
	// public function saveImg($directory, $guid, $form = 'generic'){

	// 	// a questo punto posso impostare i parametri di salvataggio
	// 	$dir = str_replace('\\', '/', \Uoowd\Param::imgStore());
	// 	// $dir .= 'User-'.elgg_get_logged_in_user_guid().'/';
	// 	$saveDir = $dir.$directory;
	// 	// if (!file_exists($saveDir)) {
	// 	//     mkdir($saveDir, 0777, true);
	// 	// }
	// 	if(! $this->createDir($saveDir)) return;
	// 	// utile da richiamare nel caso voglia cancellare la directory per via di errori successivi
	// 	// vedi metodo removeDir() e crop() 
	// 	$this->saveDir = $saveDir;
	// 	$this->guid = $guid;

	// 	$this->sticky = new \Uoowd\Sticky($form);
	// 	$sticky = $this->sticky;

	// 	// recupero il file salvato col costruttore
	// 	// NB: credo vi sia un Bug di php: $File (inteso come $_FILES) non puo' essere impostato con $sticky->setV !!!!
	// 	// ora penso al file, perche' e' un parametro obbligatorio
	// 	// controllo di avere un'immagine da salvare
	// 	foreach($_FILES as $key => $file){
	// 	    $error = elgg_get_friendly_upload_error($file['error']);
		    
	// 	    // check dell'immagine
	// 	    if(! $size =getimagesize($file['tmp_name'])){
	// 	        $msg = 'Puoi solo inserire immagini.';
	// 	    }else{
	// 	        // per il momento ho un solo file, pertanto non mi serve
	// 	        // $_FILES[$key]['extra'] = $size;
	// 	        $file['extra'] = $size;
	// 	    }

	// 	    // vari errori
	// 	    if($error) {
	// 	        // register_error($error);
	// 	        // attenzione all'ordine!
	// 	        $msg = 'Errore di caricamento dell\'immagine, siamo spiacenti';
	// 	        if(!$file['name']) $msg = 'Non hai aggiunto alcuna immagine';

	// 	    }

	// 	    if($msg){
	// 	        $sticky->_setV(array('fileError'=> $msg));
	// 	        // forward(REFERER);
	// 	        $this->status = false;
	// 	   }
	// 	   // salvo il file (attualmente dovrebbe essere l'unico) in una variabile da riutilizzare nel seguito
	// 	   $this->File = $file;
	// 	}

	// 	// parto col salvataggio dell'originale nella root del folder
	// 	// $target_file = $saveDir.$File['name'];
	// 	$sticky->setV(array('att'=>$File));
	// 	$target_file = $saveDir.$guid.'.'.pathinfo($this->File['name'], PATHINFO_EXTENSION);
	// 	// \Uoowd\Logger::addError($target_file);

	// 	// per il metodo crop()
	// 	$this->target = $target_file;

	// 	if (move_uploaded_file($this->File["tmp_name"], $target_file)) {
	// 	    $r['message'] = "File ". basename( $target_file). " salvato con successo.";
	// 	    $r['response'] = 'success';
	// 	    // svuoto la directory
	// 	    // per il momento decido di tenere un solo file per volta
	// 	    // foreach (new DirectoryIterator($saveDir) as $fileInfo) {
	// 	    //     if(!$fileInfo->isDot() && $fileInfo->getBasename()!=$File['name']) {
	// 	    //         unlink($fileInfo->getPathname());
	// 	    //     }
	// 	    // }
	// 	    $this->emptyDirBut($saveDir, $target_file);
	// 	} else {
	// 	    $r['message'] = "Purtroppo il file risulta corrotto.";
	// 	    $r['response'] = 'error';
	// 	    $er['fileError'] = "Purtroppo il file risulta corrotto.";
	// 	    $er['guid'] = $guid;
	// 	    // $er['fi_le'] = json_decode($this->File);
	// 	    $er['target'] = $target_file;
	// 	    $sticky->setV($er);
	// 	    $this->status = false;
	// 	    return;
	// 	}

	// 	// se sono qui, la creazione del file di base e' andata bene, 
	// 	// pertanto non mi rimane che croppare
		
	// 	$this->crop();

	// 	// $this->status = false;

	// }
	
	public function saveImgEach($directory, $guid, $form = 'generic', $input){
		\Uoowd\Logger::addError('inizio dentro a saveImgEach');

		// a questo punto posso impostare i parametri di salvataggio
		$dir = str_replace('\\', '/', \Uoowd\Param::imgStore());
		// $dir .= 'User-'.elgg_get_logged_in_user_guid().'/';
		$saveDir = $dir.$directory;
		// if (!file_exists($saveDir)) {
		//     mkdir($saveDir, 0777, true);
		// }
		if(! $this->createDir($saveDir)) return;
		// utile da richiamare nel caso voglia cancellare la directory per via di errori successivi
		// vedi metodo removeDir() e crop() 
		// $this->saveDir = $saveDir;
		$this->guid = $guid;

		$this->sticky = new \Uoowd\Sticky($form);
		$sticky = $this->sticky;

		// recupero il file salvato col costruttore
		// NB: credo vi sia un Bug di php: $File (inteso come $_FILES) non puo' essere impostato con $sticky->setV !!!!
		// ora penso al file, perche' e' un parametro obbligatorio
		// controllo di avere un'immagine da salvare
		// 
		// $key e' il file: file1, file2, etch etch, associato a crop_file1, crop_file2, etch etch
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
		    	\Uoowd\Logger::addError($msg);
		        $sticky->_setV(array('fileError'=> $msg));
		        // forward(REFERER);
		        $this->status = false;
		   }
		   // salvo il file (attualmente dovrebbe essere l'unico) in una variabile da riutilizzare nel seguito
		   $this->File = $file;


		// parto col salvataggio dell'originale nella root del folder
		// $target_file = $saveDir.$File['name'];
		$sticky->setV(array('att'=>$File));
		$fileDir = $saveDir.$key.'/';
		if(! $this->createDir($fileDir)) return;
		$this->saveDir = $fileDir;
		$target_file = $fileDir.$key.'.'.pathinfo($this->File['name'], PATHINFO_EXTENSION);
		// \Uoowd\Logger::addError($target_file);

		// per il metodo crop()
		$this->target = $target_file;
		$this->cropSize = $input['crop_'.$key];
		$this->baseFile = $key;
		\Uoowd\Logger::addError($target_file);
		\Uoowd\Logger::addError($fileDir);

		if (move_uploaded_file($this->File["tmp_name"], $target_file)) {
		    $r['message'] = "File ". basename( $target_file). " salvato con successo.";
		    $r['response'] = 'success';
		    \Uoowd\Logger::addError($r);
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
		    $er['guid'] = $guid;
		    // $er['fi_le'] = json_decode($this->File);
		    $er['target'] = $target_file;
		    $sticky->setV($er);
		    \Uoowd\Logger::addError($er);
		    $this->status = false;
		    return;
		}

		// se sono qui, la creazione del file di base e' andata bene, 
		// pertanto non mi rimane che croppare		
		$this->crop();

		// $this->status = false;

		}// chiusura foreach salvataggio di OGNI file
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

		// recupero il file, come una string per non farmi problemi in merito al formato
		$img = imagecreatefromstring(file_get_contents($target_file)); 

		if ( !$img ) {
		    // header('Content-Type: image/png');
		    // imagepng($im);
		    // imagedestroy($im);
		    $e['mess'] = 'errore in createfromstring';
		    $e['tg'] = $target_file;
		    $sticky->_setV($e);
		    $this->status = false;
		    return;
		}
		
		$w = imagesx($img);
		$h = imagesy($img);

		$crop = $this->cropSize;

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
		file_put_contents($saveDir.pathinfo ( $target_file , PATHINFO_FILENAME).'-crop.json', json_encode($crop));

		// dimensioni di default thumbnail
		$imsize['small'] = 100;
		$imsize['medium'] = 400;


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
		    $Fname = $sdir.$this->baseFile.'.jpg';
		    imagejpeg( $thumb , $Fname );

		    if(!$this->emptyDirBut($sdir, basename($Fname))) return;
		}

	}

	public function base64(){
		$fp = fopen($this->target, "rb");
		$fp = base64_encode(stream_get_contents($fp));
		fclose($fp);
		return $fp;
	}

	public function cropCheck(){
		if($this->status){
			$this->sticky->unsetSticky();
			return true;
		}else{
			$this->removeDir();
			return false;
		}
	}


	public function createDir($dir){
		if (!file_exists($dir)) {
		    mkdir($dir, 0777, true);
		}
		// nel caso di errori di creazione, allora ritorno un errore
		if (!file_exists($dir)){
			$this->sticky->setV(array('dirError'=>'Directory: '.$dir.' , non esiste'));
			\Uoowd\Logger::addError('Directory: '.$dir.' , non esiste');
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

		foreach (new \DirectoryIterator($dir) as $fileInfo) {
			// se e' dot la ignoro
			if($fileInfo->isDot()) continue;

			if($fileInfo->isDir()  && !rmdir($fileInfo->getPathname()) ){
				\unlinkDir($fileInfo->getPathname());
			} 

		    unlink($fileInfo->getPathname());

		}

		return rmdir($dir);
	}
	

}