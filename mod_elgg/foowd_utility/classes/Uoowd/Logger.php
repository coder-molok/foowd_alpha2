<?php

namespace Uoowd;

require_once(elgg_get_plugins_path().\Uoowd\Param::uid().'/vendor/autoload.php');



class Logger{

	public static function init(){
	
		// create a log channel
		$log = new \Monolog\Logger('Foowd');
		$log->pushHandler(new \Monolog\Handler\StreamHandler(elgg_get_plugins_path().\Uoowd\Param::uid().'/log//'.date("y-m-d").'.log', \Monolog\Logger::DEBUG));

		return $log;
	}


	
	/**
	 *	wrappo automaticamente gli add di Monolog 
	 */
	public static function __callStatic($name, $arguments){
		// se add seguito da uppercase
		if(preg_match('@add@', $name)){
			// $str = array_diff(explode(DIRECTORY_SEPARATOR, $str), explode(DIRECTORY_SEPARATOR, __DIR__));
			// $str = implode('/', $str);
			$dbg = debug_backtrace()[1];
			$str = $arguments[0].' [File: '.$dbg['file'].' ][Line: '.$dbg['line'].' ]';
			self::init()->{$name}($str);	
		} 
	}

}