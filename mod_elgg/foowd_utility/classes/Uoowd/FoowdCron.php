<?php

namespace Uoowd;

class FoowdCron{

	// utile per lavorare con le date in generale
	public static $mesi = array('1'=>'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre','dicembre');

	public static $giorni = array('domenica','lunedì','martedì','mercoledì', 'giovedì','venerdì','sabato');

	public static $types = array(
			'minute', 'fiveminute', 'fifteenmin', 'halfhour', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'reboot'
		);

	public static function register(){

		// ottengo il nome di questa classe
		$thisClass = get_called_class();

		// lo uso solamente per testare TUTTI i crontab: scrivo su specifici file
		foreach(self::$types as $typ){
			elgg_register_plugin_hook_handler('cron', $typ, array($thisClass, 'logTest') );	
			
			$method = $typ . 'Collection';
			if(method_exists($thisClass, $method)){
				elgg_register_plugin_hook_handler('cron', $typ, array($thisClass, $method) );		
			}
			
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


	/**
	 * raccolgo tutte le funzioni da eseguire ogni quarto d'ora
	 * @param  [type] $hook   [description]
	 * @param  [type] $type   [description]
	 * @param  [type] $return [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public static function halfhourCollection($hook, $type, $return, $params){
		// controllo lo stato degli ordini per l'invio: ricordarsi di assestare l'attributo $cronTab di FoowdPurchase.php
		// $solve = new \Uoowd\FoowdPurchase();
		// $solve->check();
		
		// controllo se ci sono offerte modificate da aggiornare
		$offer = new \Uoowd\FoowdOffer();
		$offer->solveEdited(); 
		
	}

}