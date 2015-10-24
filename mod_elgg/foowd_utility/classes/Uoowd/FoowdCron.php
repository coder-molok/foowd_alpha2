<?php

namespace Uoowd;

class FoowdCron{

	public static $types = array(
			'minute', 'fiveminute', 'fifteenmin', 'halfhour', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'reboot'
		);

	public static function register(){
		
		// lo uso solamente per testare TUTTI i crontab: scrivo su specifici file
		foreach(self::$types as $typ){
			elgg_register_plugin_hook_handler('cron', $typ, array('\Uoowd\FoowdCron', 'logTest') );	
		}

	}

	/**
	 * Funzione per testare il funzionamento di tutti i crontab
	 * @param  [type] $hook   [description]
	 * @param  [type] $type   [description]
	 * @param  [type] $return [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function logTest($hook, $type, $return, $params){
		$file = elgg_get_plugins_path().'foowd_utility/test/' . $type . '.txt';
		$text = new \DateTime();
		$text->setTimestamp($params['time']);
		$text = $text->format('U = Y-m-d H:i:s') . "\n";
		// error_log('altro test: '.$file. $text);
		file_put_contents($file, $text, FILE_APPEND);
		// \Uoowd\Logger::addError(func_get_args());
		// ["cron","minute","",{"time":1445696701}] 
	}

}