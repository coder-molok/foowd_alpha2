<?php


namespace Foowd;


	class Param{
	
		public static $par = array(
			'apiDom'	=> 'http://localhost/api_offerte/public_html/api/',
			'pid'		=> 'foowd_offerte'	// id del plugin
		);
	
		public static function __callStatic($name, $arguments){
			if(array_key_exists($name, self::$par)){
				 return self::$par[$name];
			}else{
				return null;
			}
		}
	}


