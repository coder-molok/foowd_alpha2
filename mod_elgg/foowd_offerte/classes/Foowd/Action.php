<?php


namespace Foowd;


abstract class Action {
		
		/**
		 * variabile per associare a ogni input il messaggio predefinito
		 *
		 * DEVONO essere inserite nell'ordine con cui si vogliono presentare nel form
		 * 
		 * @var array in form $table_field => $mesage_default
		 */
		private $par = array();

		/**
		 * variabile per associare a ogni input il messaggio di errore
		 * @var array
		 */
		private $errors = array();

		/**
		 * variabile per associare a ogni input la corrispettiva funzione di check
		 * @var array
		 */
		private $check = array();

		/**
		 * NB: check and error should be similare in $key
		 */

		/**
		 * variabile di stoccaccio per contenere tutti gli input e i loro valori
		 * @var string
		 */
		public $vars = 'null';



		/**
		 * costruttore
		 * @param array $ar array del form contenente input e valori
		 */
		public function __construct(array $childPar, array $ar = null){

			// imposto i valori predefiniti della classe figlio
			foreach ($childPar as $key => $value) {
				$this->{$key} = $value;
			}
			$this->vars = $ar;
		}



		/**
		 *	funzione per impostare la variabile $vars che la pages utilizzera'
		 *	per generare la view_form che richiama ($vars verra' passata a questa).
		 * 
		 * @return array array con tutti i valori del form
		 */
		public function prepare_form_vars(string $action) {
			// controllo se e' uno sticky form
			if (elgg_is_sticky_form($action)) {
				// ottengo tutti gli stricky value precedentemente salvati
				$sticky_values = elgg_get_sticky_values($action);
				//var_dump($sticky_values);
				foreach ($sticky_values as $key => $value) {
					$values[$key] = $value;
				}
			}

			// dico al sistema di scartare gli input di questo form
			elgg_clear_sticky_form($action);

			return $values;
		}

		/**
		 * metodo utile per creare i form velocemente
		 * 
		 * @param  [type] $field [description]
		 * @param  [type] $label [description]
		 * @param  [type] $type  [description]
		 * @param  array  $extra [description]
		 * @return [type]        [description]
		 */
		public function createField($field, $label, $type, array $extra){
			$settings = array('name' => $field, 'value' => elgg_echo($this->{$field}) );
			if(isset($extra)) $settings = array_merge($settings,$extra);
			//var_dump($settings);
			?>
			<div>
			    <label><?php echo elgg_echo($label); ?></label><div style="color:red"><?php echo elgg_echo($this->{$field.'Error'});?></div><br />
			    <?php echo elgg_view($type,$settings); ?>
			</div>
			<?php
		}

		/**
		 * creo automaticamente i membri della classe grazie all'array pubblico $var
		 * 
		 * @param  string $name il nome del campio 
		 * @return void       
		 */
		public function __get(string $name){
			if(array_key_exists($name, $this->par)){
				return elgg_extract($name, $this->vars, $this->par[$name]);
			}else{
				return elgg_extract($name, $this->vars, '');
			}

		}

		/**
		 * metodo per estrarre automaticamente i dati da servire come metodo POST
		 * 
		 * @param  string $sticky_form [description]
		 * @return [type]              [description]
		 */
		public function manageForm(string $sticky_form){
			$this->status = true;
			foreach($this->par as $field => $value){
				$var = get_input($field);
				if(isset($var)){
				 	$data[$field] = $var;

					if(!$this->checkError($field, $data[$field], $sticky_form))	$this->status = false; 
				 }
			}
			return $data;
		}

		
		/**
		 * se ci sono errori, li aggiungo all'insieme degli input del mio sticky_forms,
		 * 		in modo che possa visualizzare gli errori associati alla compilazione
		 * 
		 * @param  [type] $er  [description]
		 * @param  [type] $val [description]
		 * @return [type]      [description]
		 */
		public function checkError($er, $val, $action){
			if(array_key_exists($er, $this->check)){
				$method = $this->check[$er];
				if(!$this->$method($val))
				$_SESSION['sticky_forms'][$action][$er.'Error']=$this->errors[$er];
				return $this->$method($val);
			}else{
				return true; //perche' su di essa non devo fare controlli
			}
		}

		/**
		 * Aggiungo automaticamente gli errori ritornati dalla pagina esterna API
		 * @param array  $ar     [description]
		 * @param [type] $action [description]
		 */
		public function addError(array $ar, $action){
			foreach ($ar as $key ) {
				$_SESSION['sticky_forms'][$action][$key.'Error']=$this->errors[$key];
			}
		}

		/**
		 * per modificare a mio piacimento i parametri sticky del form
		 * @param  array  $ar     associative, $field => $value
		 * @param  [type] $action [description]
		 * @return [type]         [description]
		 */
		public function manageSticky(array $ar, $action){
			if(!isset($_SESSION['sticky_forms'][$action])) $_SESSION['sticky_forms'][$action] = array();
			foreach ($ar as $key => $value) {
				$_SESSION['sticky_forms'][$action][$key]=$value;
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

		/**
		 * check base sui tag
		 * 
		 * @param  [type]  $var [description]
		 * @return boolean      [description]
		 */
		public function isTag($var){
			// prendo i tag inseriti dal form
			$actualTags = explode(',', $var);
			$actualTags = array_unique($actualTags);	// evito eventuali ripetizioni

			// valido i tag (posso anche impostarlo lato propel)
			foreach ($actualTags as $single) {
				// prima di salvare, controllo che il tag sia di una sola parola
				// da vedere: aggiungere controllo sulla presenza di caratteri speciali
				$single = trim($single);
				if(preg_match('@ +@i', $single)){
					return false;
				}
			}
			return true;
		}
	}


