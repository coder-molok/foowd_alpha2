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
		
		$level = elgg_get_plugin_setting('LEVEL', \Uoowd\Param::uid() );
		if(! $level) $level = 'ERROR';
		// il livello e' una costante di classe: nota come viene invocata nel pushHandler
		$func = '\Monolog\Logger::'.$level;
		// create a log channel
		$log = new \Monolog\Logger('ElggUtilityFoowd');
		// $targetFile = elgg_get_plugins_path().\Uoowd\Param::uid().'/log/'.date("y-m-d").'.log';
		$targetFile = elgg_get_plugins_path().'foowd_utility'.'/log/'.date("y-m-d").'.log';
		// error_log($targetFile);
		$log->pushHandler( new \Monolog\Handler\StreamHandler( $targetFile , constant($func) )  );
		$log->pushHandler( new \Monolog\Handler\ErrorLogHandler(0, constant($func)) );

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
			if(is_object($arguments[0]) || is_array($arguments[0])) $arguments[0] = json_encode($arguments[0]);
			$str = $arguments[0].' [File: '.$dbg['file'].' ][Line: '.$dbg['line'].' ]';
			self::init()->{$name}($str);
		} 
	}


	/**
	 *  funzione che permette agli amministratori di visualizzare l'elenco di tutti i log
	 */
	public static function displayLog(){
		elgg_admin_gatekeeper();
		$dir = elgg_get_plugins_path().\Uoowd\Param::uid().'/log/';
		$logs = array();
		foreach(new \DirectoryIterator($dir) as $file){
			if($file->isFile()) array_push($logs, $dir.$file->getBasename());
		}

		$logs = array_reverse($logs);

		foreach($logs as $file){
			echo '<h1>'.basename($file).'</h1>';
			$file = file($file);
			$file = array_reverse($file);
			foreach($file as $f){
				echo '<pre>'.$f.'</pre>';
			}
		}
		// \Fprint::r($logs);
	}

}