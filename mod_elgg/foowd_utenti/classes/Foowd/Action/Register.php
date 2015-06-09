<?php

namespace Foowd\Action;

	class Register extends \Uoowd\Action{
		
		/**
		 * variabile per associare a ogni input il messaggio predefinito e 
		 * riprodurre la struttura dello schema
		 *
		 * I nomi dei campi DEVONO essere i metodi utilizzati via propel
		 * 
		 * @var array
		 */
		private $par = array(
			'Name'					=> '',
			'ExternalId'			=> '',		
			'Genre'					=> array("standard", "offerente")
		);

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array(
			'Genre' 		=> 'errore nel genere',
			'ExternalId'	=> 'errore nell\' extid',
		);

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array(
			'Genre'		=> 'isEnum'
		);

		/**
		 * variabile di stoccaccio per contenere tutti gli input e i loro valori
		 * @var string
		 */
		//public $vars = 'null';

		public function __construct(array $ar = null){
			// passo i parametri al padre
			 parent::__construct(get_object_vars($this), $ar);
		}
	}


