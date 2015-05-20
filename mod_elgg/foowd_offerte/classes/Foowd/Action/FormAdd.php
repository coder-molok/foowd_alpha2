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
			'Minqt'			=> '',
			'Maxqt'			=> '',
			'Created'		=> '',
			'Modified'		=> '',
			'State'			=> '',

			'Tag'			=> '', 	// extra non appartenente alla tabella sql,
									// ma in ogni caso necessario nella compilazione del form

		);


		private $needle = array(
			"Price",
			"Tag",
			"Minqt"
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

		
		public function __construct(array $ar = null){
			// passo i parametri al padre
			 parent::__construct(get_object_vars($this), $ar);
		}

		/**
		 * metodo per convertire i campi di spinner in un unica cifra razionale
		 * @param  [type] $sticky_form [description]
		 * @return [type]              [description]
		 */
		public function manageInput($sticky_form){
			$numeric = array("Price", "Minqt","Maxqt");
			// \Uoowd\Logger::addNotice('$quantity');
			foreach($numeric as $key){
				$set = 1; // as true; 0 as false
				if(get_input($key.'-integer')===""){
					$set *= 0;
				}
				if($set){
					// imposto i valori di input
					if(get_input($key.'-decimal') === "") set_input($key.'-decimal', 0);
					$quantity = get_input($key.'-integer').'.'.get_input($key.'-decimal');
					set_input($key,  $quantity);
					// imposto i valori dello sticky form
					$this->manageSticky(array($key=>$quantity), $sticky_form);
				}
			}
		}
	}


