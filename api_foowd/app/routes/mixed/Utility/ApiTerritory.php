<?php

namespace Mixed\Utility;

class ApiTerritory{

	public function __construct($app){
		$fileName = __DIR__.'/elenco-comuni-italiani.csv';
		$csvData = file_get_contents($fileName);
		// error_log($csvData);
		$lines = explode(PHP_EOL, $csvData);

		// la prima riga contiene le intestazioni, pertanto non la considero
		// var_dump($lines[0]);
		unset($lines[0]);



		$regioni = array();
		$province = array();
		$sigle = array();
		$path = array();

		$ar = array();

		// NB: il codice e' appesantito dal fatto che avendo lettere accentate devo creare una corrispondenza indice - valore come per le tabella mysql, creando cosi' relazioni 1 a molti
		foreach ($lines as $line) {

			// svolgo l'encode dei caratteri
			// echo $line;
			$echo = false;
			// if(preg_match("@forl@i", $line)) $echo = true;
			// stringa decodificata
			$line =mb_convert_encoding($line, 'UTF-8',
		          mb_detect_encoding($line, 'UTF-8, ISO-8859-1', true));;
		    $row = str_getcsv($line, ';');
		    // if($echo) var_dump($row);
			
		    // raccolgo i dati
		    $citta = $row[5];
		    $regione = $row[9];
		    // le province sono citta' metropolitane
		    $provincia = ($row[11] == '-') ? $row[10] : $row[11] ;
		    $sigla = $row[13];
		    // codice catastale
		    $code = $row[17];
						
			if(!in_array($provincia, $sigle)) $sigle[$provincia] = $sigla;

		    // array associativo completo
			if(strlen($regione) > 4 && !array_key_exists($regione, $path)) $path[$regione] = array();
			if(!array_key_exists($provincia, $path[$regione]) && strlen($provincia) > 4) $path[$regione][$provincia] = array();
			if(strlen($citta) > 2){
				$path[$regione][$provincia][] = array(
					'name' => $citta,
					'code' => $code
				);
			}			
		}
		// // Note this method returns a boolean and not the array
		function recur_ksort(&$array, $deep = 0) {
			$d = $deep + 1 ;

			// deep e questo sono degli extra aggiunti per controllare il nome
			if($d == 3){
				usort($array, function($a, $b){	return strcasecmp($a['name'], $b['name']); });
				return;
			}

		   	foreach ($array as &$value) {
		    	if (is_array($value)){
		       		recur_ksort($value, $d);
		   		}else{
		   			sort($array);
		   		}
		   }
		   return ksort($array);
		}
		// ordino l'array associativo
		recur_ksort($path);

		// RISPOSTA
		// header('Content-Type: application/json'); // gia impostato in index
		// NB: salvare in un file a parte
		echo json_encode($path);
	}
}