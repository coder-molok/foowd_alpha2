<?php

namespace Uoowd;

/**
 * 	Classe che implementa in maniera piu agevole lo sticky form: 
 * 	quello di elgg non mi piace, pertanto ricreo il meccanismo a modo mio
 */

class Sticky{
	
	public $form =null;
	// public $status=false;

	/**
	 * imposto il nome del form per non richiamarlo ogni volta
	 * @param [type] $form [description]
	 */
	public function __construct($form){
		$this->form = $form;
		// var_dump($his->form);
	}

	/**
	 * imposto i valori 
	 * @param [type] $array [description]
	 */
	public function setV($ar){
	    foreach ($ar as $key => $value) {
	        $_SESSION['sticky_forms'][$this->form][$key] = $value;
	    }
	}

	/**
	 * imposto i valori 
	 * @param [type] $array [description]
	 */
	public function getV($val){
	    return $_SESSION['sticky_forms'][$this->form][$val];
	}

	/**
	 * elimino i valori
	 * @param  [type] $array [description]
	 * @return [type]        [description]
	 */
	public function unsetV($array){
	    foreach ($array as $key) {
	        unset($_SESSION['sticky_forms'][$this->form][$key]);
	    }
	}

	/**
	 * elimino tutto il form
	 * @return [type] [description]
	 */
	public function unsetSticky(){
		unset($_SESSION['sticky_forms'][$this->form]);
	}

	/**
	 * elimino tutto il form
	 * @return [type] [description]
	 */
	public static function unsetForce($form){
		unset($_SESSION['sticky_forms'][$form]);
	}

	public function toArray(){
		return $_SESSION['sticky_forms'][$this->form];
	}

	/**
	 * di default provo ad eseguire i metodi.
	 * Quelli preceduti da underscore verranno eseguiti se il form non e' settato ritornano false;
	 * @param  [type] $method [description]
	 * @param  [type] $args   [description]
	 * @return [type]         [description]
	 */
	public function __call($method, $args){

		// per comodita' uso una closure
		// $cls = &$this;
		// $check = function() use (&$cls, &$method) {
		// 	var_dump($method);
		// 	return method_exists($cls,$method);
		// };
		// $method = 'lolo';
		// $check();

		if(preg_match('@^_@', $method)){
			if( is_null($this->form)) return false;
			$method = preg_replace('@^_@', '', $method);
		}

		
		if(method_exists($this,$method)) return call_user_func_array(array($this, $method), $args);
		
		// se il metodo non esiste allora triggero un errore
		trigger_error("Method ->$method<- doesn't Exists", E_USER_ERROR);
	}

	public static function check($form){
		$set = isset($_SESSION['sticky_forms'][$form]);
		$empty = empty($_SESSION['sticky_forms'][$form]);
		return ($set || $empty);
	}

}