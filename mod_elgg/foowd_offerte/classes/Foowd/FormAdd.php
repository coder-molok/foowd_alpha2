<?php


namespace Foowd;


	class FormAdd {
		
		/**
		 * variabile per associare a ogni input il messaggio predefinito
		 * @var array
		 */
		private $par = array(
			'title' 		=> 'immetti titolo...',
			'description'	=> 'inserire descrizione',
			'tags'			=> '',
			'import'		=> 'cifre decimali separate dalla virgola...'
		);

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array(
			'title' 		=> 'errore nell\' immisione del titolo',
			'description'	=> 'errore nell\' immisione della descrizione',
			'tags'			=> '',
			'import'		=> 'ricorda: due cifre decimali separate dalla virgola...'
		);

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array(
			'import'		=> 'isCash'
		);

		/**
		 * variabile di stoccaccio per contenere tutti gli input e i loro valori
		 * @var string
		 */
		public $vars = 'null';



		/**
		 * costruttore
		 * @param array $ar array del form contenente input e valori
		 */
		public function __construct(array $ar = null){
			if(is_null($ar)) return;
			$this->vars = $ar;
		}


		/**
		 *	funzione per impostare la variabile $vars che la pages utilizzera'
		 *	per generare la view_form che richiama ($vars verra' passata a questa).
		 * 
		 * @return array array con tutti i valori del form
		 */
		public function prepare_form_vars() {
			
			// controllo se e' uno sticky form
			if (elgg_is_sticky_form('foowd_offerte/add')) {
				// ottengo tutti gli stricky value precedentemente salvati
				$sticky_values = elgg_get_sticky_values('foowd_offerte/add');
				foreach ($sticky_values as $key => $value) {
					$values[$key] = $value;
				}
			}

			// dico al sistema di scartare gli input di questo form
			elgg_clear_sticky_form('foowd_offerte/add');

			return $values;
		}


		/**
		 * creo automaticamente i membri della classe grazie all'array pubblico $var
		 * 
		 * @param  string $name il nome del campio 
		 * @return void       
		 */
		public function __get($name){
			if(array_key_exists($name, $this->par)){
				return elgg_extract($name, $this->vars, $this->par[$name]);
			}else{
				return elgg_extract($name, $this->vars, '');
			}

		}

		
		/**
		 * se ci sono errori, li aggiungo all'insieme degli input del mio sticky_forms,
		 * 		in modo che possa visualizzare gli errori associati alla compilazione
		 * 
		 * @param  [type] $er  [description]
		 * @param  [type] $val [description]
		 * @return [type]      [description]
		 */
		public function checkError($er, $val){
			if(array_key_exists($er, $this->check)){
				$method = $this->check[$er];
				if(!$this->$method($val))
				$_SESSION['sticky_forms']['foowd_offerte/add'][$er.'Error']=$this->errors[$er];
				return $this->$method($val);
			}
		}


		/**
		 * controllo che ci siano solo due cifre decimali precedute dalla virgola
		 * @param  [type]  $var [description]
		 * @return boolean      se true, la validazione e' andata a buon fine
		 */
		public function isCash($var){
			if (preg_match('/^\d+\,\d{2,2}$/', $var)){
				return true;
			}else{
				return false;
			}
		}


	}


