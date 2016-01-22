<?php


namespace Uoowd;


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
		 * variabile per specificare i parametri obbligatori
		 * @var array
		 */
		private $needle = array();

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
			if($ar){
				foreach ($ar as $key => $value) {
					$this->{$key} = $value;
				}
			}
		}



		/**
		 *	funzione per impostare la variabile $vars che la pages utilizzera'
		 *	per generare la view_form che richiama ($vars verra' passata a questa).
		 * 
		 * @return array array con tutti i valori del form: vuoto nel caso il form non sia sitcky
		 */
		public function prepare_form_vars($action) {
			// controllo se e' uno sticky form
			if (elgg_is_sticky_form($action)) {
				// ottengo tutti gli stricky value precedentemente salvati
				// var_dump(elgg_get_sticky_values($action));
				// $sticky_values = array_merge(elgg_get_sticky_values($action), $_SESSION['sticky_forms'][$action]);
				$sticky_values = elgg_get_sticky_values($action);
				// vsar_dump($sticky_values);
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
		/*public function createField($field, $label, $type, array $extra){
			$settings = array('name' => $field, 'value' => elgg_echo($this->{$field}) );
			if(isset($extra)) $settings = array_merge($settings,$extra);
			// var_dump($settings);
			?>
			<div>
			    <label><?php echo elgg_echo($label); ?></label><div style="color:red"><?php echo elgg_echo($this->{$field.'Error'});?></div><br />
			    <?php echo elgg_view($type,$settings); ?>
			</div>
			<?php
		}*/
		public function createField($field, $label, $type, $extra=array()){
			// se non e' creato, crelo lo sticky
			if(!is_object($this->sticky)) $this->sticky = new \Uoowd\Sticky($this->vars['sticky']);

			// se il field non ha valore, provo a inserirlo mediante sticky
			if(!isset($this->{$field}) || $this->{$field}=='') $this->{$field} = $this->sticky->getV($field);
			
			if(!isset($this->{$field.'Error'}) || $this->{$field.'Error'}=='') $this->{$field.'Error'} = $this->sticky->getV($field.'Error');
			$setVal = is_string($this->{$field}) ? $this->{$field} : '' ;
			$settings = array('name' => $field, 'value' => elgg_echo($setVal) );
			if(isset($extra)) $settings = array_merge($settings,$extra);
			// var_dump($settings);
			?>
			<div>
			    <label for="<?php echo $field; ?>"><?php echo elgg_echo($label); ?></label><div style="color:red">
			    <?php echo elgg_echo($this->{$field.'Error'} );?></div>
			    <?php 
			    	$method = 'hookCreate'.$field;
			    	if(method_exists($this, $method)){
			    		call_user_func(array($this, $method ), $type, $extra);
			    	}else{
			    		echo elgg_view($type,$settings);
			    	}

			    	?>
			</div>
			<?php
		}


		/**
		 * creo automaticamente i membri della classe grazie all'array pubblico $var
		 * 
		 * @param  string $name il nome del campio 
		 * @return void       
		 */
		public function __get($name){
			if(!is_array($this->vars)) return null;
			if(!is_array($this->par)) return null;
			if(array_key_exists($name, $this->par)){
				return elgg_extract($name, $this->vars, $this->par[$name]);
			}else{
				if(!is_array($this->par)) return null;
				return elgg_extract($name, $this->vars, '');
			}

		}

		/**
		 * metodo per estrarre automaticamente i dati da servire come metodo POST
		 * 
		 * @param  string $sticky_form [description]
		 * @return [type]              [description]
		 */
		public function manageForm($sticky_form){
			$this->status = true;
			// metodo specifico della classe, utile per fare un pre assestamento dei dati input
			if(method_exists($this,'hookManage')) $this->hookManage($sticky_form);
			foreach($this->par as $field => $value){
				$var = get_input($field);
				if(isset($var)){
				 	$data[$field] = $var;

					// \Uoowd\Logger::addNotice($field." => ".$var);
					if(!$this->checkError($field, $data[$field], $sticky_form)){
						$this->status = false; 
					}
				 }
			}

			// se ho dei parametri obbligatori allora controllo che siano stati immessi
			if(isset($this->needle)){
				$e = array_diff($this->needle, array_keys($data) );
				// \Uoowd\Logger::addNotice($e);
				foreach($e as $key){
					$str = $key.'Error';
					$err = 'Il campo e\' obbligatorio';
					$this->manageSticky(array($str=>$err), $sticky_form);
					\Uoowd\Logger::error($err);
					$this->status = false; 
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
			// \Uoowd\Logger::addNotice($val);
			if(array_key_exists($er, $this->check)){
				$method = $this->check[$er];
				if(!$this->$method($val))
				$_SESSION['sticky_forms'][$action][$er.'Error']=$this->errors[$er];
				// \Uoowd\Logger::addNotice($_SESSION['sticky_forms'][$action]);
				// \Uoowd\Logger::addNotice($er);
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
		 *
		 * NB: 	stai attento a quando la funzione usa gli sticky_value e quando i normali input:
    	 * 		con manage sticky potrei perdere la coerenza
		 * 
		 * @param  array  $ar     associative, $field => $value
		 * @param  string $action the sticky form name
		 * @return void
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
			// \Uoowd\Logger::addNotice($var);
			if (preg_match('/^\d{1,8}(\.\d{0,2})?$/', $var)){
				return true;
			}else{
				return false;
			}
		}

		/**
		 * controllo che ci siano solo due cifre decimali precedute dalla virgola
		 * @param  [type]  $var [description]
		 * @return boolean      se true, la validazione e' andata a buon fine
		 */
		public function isQt($var){
			// \Uoowd\Logger::addNotice($var);
			if (preg_match('/^\d{1,5}(\.\d{0,3})?$/', $var)){
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

			// se vuoto, ritorna errore in quanto almeno un tag ci deve essere
			if($var === "") return false;

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

		/**
		 * check enum values from $this->par[$key]
		 * @param  String  $var string in form $key-$value, where $key is in $this->par
		 * @return boolean      [description]
		 */
		public function isEnum($var){
			$var = explode('-', $var);
			if(in_array($var[1], $this->par[$var[0]])){
				return true;
			}else{
				return false;
			} 
		}

		/**
		 * check that Maxqt >= Minqt.
		 * This happens only if tha Maxqt is set in forms, otherwise that's not even set between data to send to API service.
		 * @param  [type]  $var [description]
		 * @return boolean      [description]
		 */
		public function isMax($var){
			if(get_input('Minqt') > $var && get_input('Maxqt')!=='' ){
				return false;
			}
			return true;
		}

		/**
		 * check if is date in format yyyy-MM-dd hh:mm:ss
		 * @param  [type]  $var [description]
		 * @return boolean      [description]
		 */
		public function isDateTime($var){
			if( preg_match('@^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}@', $var) || $var === '' ){
				return true;
			}
			$str = 'Data di scadenza non valida: ' . $var;
			// \Uoowd\Logger::addError($str);
			register_error($str);
			return false;
		}		



	}


