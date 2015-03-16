<?php

namespace Foowd\Action;

	class FormAdd extends \Foowd\Action{
		
		/**
		 * variabile per associare a ogni input il messaggio predefinito
		 * @var array
		 */
		private $par = array(
			'name' 		=> 'immetti titolo...',
			'description'	=> 'inserire descrizione',
			'price'		=> 'cifre decimali separate dalla virgola...',
			'tags'			=> '',
		);

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array(
			'name' 		=> 'errore nell\' immisione del titolo',
			'description'	=> 'errore nell\' immisione della descrizione',
			'price'		=> 'ricorda: due cifre decimali separate dalla virgola...',
			'tags'			=> 'i tags possono essere solo singole parole separate da virgola...',
		);

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array(
			'price'		=> 'isCash'
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


