<?php

namespace Foowd\Action;

	class FormAdd extends \Uoowd\Action{
		
		/**
		 * variabile per associare a ogni input il messaggio predefinito e 
		 * riprodurre la struttura dello schema
		 *
		 * I nomi dei campi DEVONO essere i metodi utilizzati via propel
		 * 
		 * @var array
		 */
		private $par = array(
			'Id'			=> '',		
			'Name' 			=> 'immetti titolo...',
			'Description'	=> 'inserire descrizione',
			'Publisher'		=> '',		
			'Price'			=> '',
			'Minqt'			=> 'valore obbligatorio, con massimo 3 cifre decimali e una specificata',
			'Maxqt'			=> '',
			'Created'		=> '',
			'Modified'		=> '',
			'State'			=> '',

			'Tag'			=> '', 	// extra non appartenente alla tabella sql,
									// ma in ogni caso necessario nella compilazione del form

		);

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array(
			'Name' 			=> 'errore nell\' immisione del titolo',
			'Description'	=> 'errore nell\' immisione della descrizione',
			'Price'			=> 'massimo 8 cifre + 2 decimali...',
			'Tag'			=> 'i tags possono essere solo singole parole separate da virgola...',
		);

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array(
			'Price'		=> 'isCash',
			'Tag'		=> 'isTag'
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


