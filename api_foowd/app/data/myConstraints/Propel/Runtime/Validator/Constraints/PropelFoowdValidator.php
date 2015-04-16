<?php
 
 namespace Propel\Runtime\Validator\Constraints;

 use Symfony\Component\Validator\Constraint;
 use Symfony\Component\Validator\ConstraintValidator;

 /**
  * Foowd personal parameter checking
  */
 
 class PropelFoowdValidator extends ConstraintValidator{



	/**
	 *  array chiave => messaggio d'errore
	 *  @var array
	 */
     public $msg = array(
     		"integer"	=>	"Data must be integer greater than zero",
        "enum"    =>  "I valori ammissibili sono solamente: '%string%'",
        "isCash"  =>  "Il valore deve essere costituito da due cifre decimali",
        "isQt"    =>  "Il valore deve essere costituito da una fino a tre cifre decimali",
     		);


     /**
      * validazione: invoca un opportuno tipo
      * 
      * @param  String     $value      valore istanziato
      * @param  Constraint $constraint oggetto constraint PropelFoowd
      */
     public function validate($value, Constraint $constraint) {

        //var_dump($constraint);

       	if(! $this->{$constraint->type}($value, $constraint)){

        		$this->context->buildViolation($this->msg[$constraint->type])
        		    ->setParameter('%string%', $value)
        		    ->addViolation();

        }
        
    }


    /**
     * controllo se e' intero
     * 
     * @param  String 	$value 	valore istanziato
     * @return Bool     		true or false
     */
    public function integer($value, $constraint){
	
       	return (is_int($value) && $value > 0);
        
    }


    /**
     * Metodo per la gestione degli elenchi numerati.
     *
     * In propel viene richiamato mediante il validate. Vedi Schema.xml
     * 
     * @param  String &$value       elenco dei valori ammissibili, separato con virgola. Nota il passaggio By Reference per via di come generare il messaggio d'errore
     * @param  Class $constraint    Classe Constraint associata alla presente classe.
     * @return Bool                 true se la validazione non da problemi. Con false impedisce il salvataggio nel DB
     */
    public function enum(&$value, $constraint){

      // se non l'ho segnato, vuol dire che non e' obbligatorio
      if(is_null($value)) return true;

      // se invece l'ho esplicitato, allora devo verificare che sia presente nella lista dello schema.xml
      if(!isset($constraint->list)) return false;
      //var_dump($constraint->list);
      $need = array_map('trim', explode( ',' , $constraint->list)  );
      if(in_array($value, $need)){
        return true;
      }else{
        $value = $constraint->list;
        return false;
      }
    
    }

    /**
     * controllo che ci siano solo due cifre decimali precedute dalla virgola
     * @param  [type]  $var [description]
     * @return boolean      se true, la validazione e' andata a buon fine
     */
    public function isCash($value, $constraint){
      // echo $value;
      // if (preg_match('/^\d{1,8}\.\d{2,2}$/', $value)){
        return true;
      // }else{
      //   return false;
      // }
    }

    /**
     * controllo sulle quantita, decimal(8,3)
     * @param  [type]  $var [description]
     * @return boolean      se true, la validazione e' andata a buon fine
     */
    public function isQt($value, $constraint){
      //var_dump($constraint);
      // qui controllo solo il formato, ma non devo garantire l'esistenza
      // l'esistenza, inteso come obbligo, viene controllata grazie ai fari $needle_<metodo>
      // if(is_null($value)) return true;

      // if (preg_match('/^\d{1,5}\.\d{1,3}$/', $value)){
        return true;
      // }else{
      //   return false;
      // }
    }
 

 }


 // public function isValid($value, Constraint $constraint)
 // {
 //     if ('propelorm.org' === strstr($value, 'propelorm.org')) {
 //         return false;
 //     } else {
 //         $this->setMessage($constraint->message);

 //         return true;
 //     }
 // }


 // class UniqueValidator extends ConstraintValidator
 // {
 //     public function validate($value, Constraint $constraint)
 //     {
 //         if (null === $value) {
 //             return;
 //         }

 //         $className  = $this->context->getClassName();
 //         $tableMap   = $className::TABLE_MAP;
 //         $queryClass = $className . 'Query';
 //         $filter     = sprintf('filterBy%s', $tableMap::translateFieldName($this->context->getPropertyName(), TableMap::TYPE_FIELDNAME, TableMap::TYPE_PHPNAME));

 //         if (0 < $queryClass::create()->$filter($value)->count()) {
 //             $this->context->addViolation($constraint->message);
 //         }
 //     }
 // }