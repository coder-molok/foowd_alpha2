<?php


namespace Foowd;


	class Param{
	
		public static $par = array(
			'apiDom'	=> 'http://localhost/api_foowd/public_html/api/',	// path to API
			'pid'		=> 'foowd_offerte',									// id del plugin
			'dbg'		=> 0												// per visualizzare messaggi extra. Definito anche nel pannello utente, come apiDom
		);
	
		public static function __callStatic($name, $arguments){
			if(array_key_exists($name, self::$par)){
				 return self::$par[$name];
			}else{
				return null;
			}
		}


		/**
		 * scrivo un messaggio di errore per tracciare il file.
		 * @return null or string
		 */
		public static function dbg(){

			// se il valore e' impostato in Elgg, ovvero nei settings del plugin, allora lo tengo tale e quale, 
			// altrimenti uso il mio default
			@ $var = elgg_get_plugin_setting('dbg', \Foowd\Param::pid() );
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
		public static function logger($str){
			date_default_timezone_set('Europe/Rome'); 
			$file = __DIR__.'/../../log/'.self::pid().'-'.date("y-m-d").'.log';
			$old = file_get_contents($file); 
			$log = "[". date("D M j G:i:s T Y"). "] " . print_r($str, true);   
			$log .= " \n"; 
			file_put_contents($file, $log.$old);   
		}


	}


