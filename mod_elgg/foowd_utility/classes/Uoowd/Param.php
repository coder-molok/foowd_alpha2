<?php


namespace Uoowd;


	class Param{
	
		public static $par = array(
			'apiDom'	=> 'http://localhost/api_foowd/public_html/api/',	// path to API
			'uid'		=> 'foowd_utility',									// id del plugin
			'dbg'		=> 0,												// per visualizzare messaggi di errore fronthand (scritte rosse). Definito anche nel pannello utente, come apiDom
			'imgStore'	=> 'FoowdStorage',									// folder in cui salvare le immagini
			'tags'		=> 'tags.json',										// dove salvare il json contenente i tags
			'utilAMD'	=> 'mod/foowd_utility/js/utility.settings.amd.js',	// file js contenente i settings e che viene aggiornato ad ogni salvataggio
			'pageAMD' 	=> '/mod/foowd_utility/js/foowd.pages.amd.js',		// file js contenente l'elenco delle pagine di navigazione
			'unitAMD' 	=> '/mod/foowd_offerte/js/foowd.unit.amd.js',		// file js contenente l'elenco delle unita' di misura
		);
	
		public static function __callStatic($name, $arguments){
			if(array_key_exists($name, self::$par)){
				 return self::$par[$name];
			}else{
				return null;
			}
		}

		/**
		 * find plugin id: the current plugin, if is a foowd plugin
		 * @return [type] [description]
		 */
		public static function pid(){
			$trace = self::findLastPlug();
			// \Fprint::r($trace);
			$file = str_replace('\\','/', $trace['file']);
			$file = explode( '/',$file);
			// blocco a -1 poiche' confronto ogni elemento col suo successivo
			for($i=0; $i< count($file)-1; $i++){
				$pid = false;
				$mod = preg_match('@^mod.*$@', $file[$i]);
				$foowd = preg_match('@^foowd.*$@', $file[$i+1]);
				if($mod && $foowd){
					$pid = $file[$i+1];
				}else{
					continue;
				}
				return $pid;
			}
		}


		/**
		 * scrivo un messaggio di errore per tracciare il file.
		 * @return null or string
		 */
		public static function dbg(){

			// se il valore e' impostato in Elgg, ovvero nei settings del plugin, allora lo tengo tale e quale, 
			// altrimenti uso il mio default
			@ $var = elgg_get_plugin_setting('dbg', \Uoowd\Param::pid() );
			if(isset( $var )){
				//$var = elgg_get_plugin_setting('dbg', \Foowd\Param::pid() );
			}else{
				$var = self::$par['dbg'];
			}
			
			$bt =  debug_backtrace();
			$check = null;
			if($var) $check = 'File: '.$bt[0]['file'].' , Line '.$bt[0]['line'];
			
			return $check;
		}

		/**
		 * semplice logger per salvare la stringa passata nella directory "log/" del presente plugin
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		// public static function logger($str){
		// 	date_default_timezone_set('Europe/Rome'); 
		// 	//$file = __DIR__.'/../../log/'.self::pid().'-'.date("y-m-d").'.log';
		// 	$file = elgg_get_plugins_path().self::pid().'/log//'.self::pid().'-'.date("y-m-d").'.log';
		// 	$old = file_get_contents($file); 
		// 	// var_dump($old);
		// 	$log = "[". date("D M j G:i:s T Y"). "] " . print_r($str, true)." \n\r";   
		// 	// var_dump($log);
		// 	$str = $log.$old;
		// 	// var_dump($str);
		// 	file_put_contents($file, $str);   
		// }

		/**
		 * trovo l'ultima pagina chiamante: mi permette di tracciare l'ultimo plugin foowd che la chiama.
		 * @return [type] [description]
		 */
		public static function findLastPlug(){
			$bt =  debug_backtrace();
			foreach($bt as $trace){
				// \Fprint::r($trace['file']);
				// if(!preg_match('@foowd_.*@',$trace['file'])) break;
				if(!isset($trace['file']) || !preg_match('@foowd_.*@',$trace['file'])) break;
				$check = $trace;
			}
			// \Fprint::r($bt);
			return $check;
		}

		public static function imgStore(){

			$store = elgg_get_root_path();
			$store = rtrim($store, '/');
			$store = explode( '/', $store);
			unset($store[count($store)-1]);
			$store = implode($store, '/');
			$store .= '/'.self::$par['imgStore'].'/';
			if (!file_exists($store)) {
			    mkdir($store, 0777, true);
			}
			return str_replace('\\', '/', $store);
		}

		public static function userStore($guid, $web = null){
			if($web == null){
				$store = self::imgStore();
			}else{
				$store = elgg_get_site_url().self::page()->foowdStorage;
			}
			$store .= 'User-'.$guid.'/';	
			return str_replace('\\', '/', $store);
		}

		public static function pathStore($guid , $str, $web = null){
			if($str === 'profile') $dir='profile';
			if($str === 'avatar') $dir='avatar';
			if($str === 'offers') $dir='offers';
			return self::userStore($guid, $web).$dir.'/';
		}

		public static function offerUrl($id){
			return elgg_get_site_url().'detail?productId='.$id;
		}

		public static function publisherUrl($id){
			return elgg_get_site_url().'producer?producerId='.$id;	
		}


		public static function tags(){
			return elgg_get_plugins_path().'foowd_utility/views/default/plugins/foowd_utility/'.self::$par['tags'];
		}


		/**
		 * restituisco la pagina specifica recuperando tutto da un file json
		 * @param  [type] $page [description]
		 * @return [type]       [description]
		 */
		public static function JSON_AMD($file){
			// var_dump($file);
			$json = false;
			foreach($file as $row => $line){
				if($json && $row!=count($file)-1){
					$line = preg_replace('@(//.*|/\*.*\*/)@','', $line); // tolgo i commenti: sia /*... */ che // ...
					// $line = preg_replace('@@','', $line); // tolgo i commenti
					$json .= $line;	
				} 
				if(preg_match('@^define\(@', $line)) $json = ' ';
			}
			// $json = preg_replace('@( |\n|\r|\t)@','',$json);
			// var_dump($json);
			$json = json_decode($json);
			// var_dump($json);

			return $json;
		}

		/**
		 * oggetto ottenuto dal plugin javascript foowd.pages.amd.js
		 * @return object oggetto stdClass (lo stesso del plugin js)
		 */
		public static function page(){
			$file = file(elgg_get_root_path().\Uoowd\Param::pageAMD());
			return self::JSON_AMD($file);
		}

		/**
		 * ritorna un oggetto come page, ma all'indirizzo IP sostituisco i dns
		 * (e' un giro contorto per testare funzionalita' socials)
		 * 
		 * @return objet stdClass con path dns
		 */
		public static function pageDNS(){
			$dns = 'foowd.accaso.eu';
			// impostato come web redirect a 5.196.228.146/elgg-1.10.4  (no slash finale)
			// $dns = 'web-foowd.ddns.net';
			$site = elgg_get_site_url();
			// sostituisco ip con $dns
			$site = preg_replace('@(\d{1,3}\.){3,}\d{1,3}@',$dns,$site);
			$pages = self::page(); 
			foreach ($pages as $key => $value) {
				if(is_string($value)){
					$pages->{$key} = $site . $value;
				} 
			}
			return $pages;
		}

		/**
		 * ritorna un oggetto come page e IP 
		 * 
		 * @return objet stdClass con path http://IP
		 */
		public static function pageIP(){
			$dns = 'foowd.accaso.eu';
			$site = elgg_get_site_url();
			$pages = self::page(); 
			foreach ($pages as $key => $value) {
				if(is_string($value)){
					$pages->{$key} = $site . $value;
				} 
			}
			return $pages;
		}

		public static function unit(){
			$file = file(elgg_get_root_path().\Uoowd\Param::unitAMD());
			return self::JSON_AMD($file);
		}


		public static function checkFoowdPlugins(){
			// controllo che i plugin foowd siano attivi, 
			// ma solo se l'ho abilitato nei settings di foowd_utility
			if(elgg_get_plugin_setting('forceActivateAll', 'foowd_utility')){
				foreach( new \DirectoryIterator(elgg_get_plugins_path()) as $plug){
					if(preg_match('@foowd_@', $plug->getFilename())){
						// il filename coincide col plug id
						// ottengo l'entita' plugin
						$plg=elgg_get_plugin_from_id($plug->getFilename());
						if(!$plg->isActive()){
							$plg->activate();
							// register_error('Plugin '.$plug->getFilename().' non attivo');
						} 
					} 
				}
			}
		}

	}


