<?php

namespace Uoowd;

require_once(elgg_get_plugins_path().\Uoowd\Param::uid().'/vendor/autoload.php');


// Levels by RFC 5424
// DEBUG (100): Detailed debug information.
// INFO (200): Interesting events. Examples: User logs in, SQL logs.
// NOTICE (250): Normal but significant events.
// WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
// ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
// CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
// ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
// EMERGENCY (600): Emergency: system is unusable.



class Logger{

	public static function init(){
	
		// create a log channel
		$log = new \Monolog\Logger('Foowd');
		$log->pushHandler(new \Monolog\Handler\StreamHandler(elgg_get_plugins_path().\Uoowd\Param::uid().'/log//'.date("y-m-d").'.log', \Monolog\Logger::NOTICE));

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