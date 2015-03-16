<?php


namespace Foowd;


	class Param{
	
		private static $par = array(
			'apiDom'	=> 'http://localhost/api_offerte/public_html/api/v1/offers'
		);
	
		public static function __callStatic($name, $arguments){
			if(array_key_exists($name, self::$par)){
				return self::$par[$name];
			}else{
				return false;
			}
		}

	}


