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
     		"integer"	=>	"Data must be integer greater than zero"
     		);


     /**
      * validazione: invoca un opportuno tipo
      * 
      * @param  String     $value      valore istanziato
      * @param  Constraint $constraint oggetto constraint PropelFoowd
      */
     public function validate($value, Constraint $constraint) {

       	if(! $this->{$constraint->type}($value)){

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
    public function integer($value){
	
       	return (is_int($value) && $value > 0);
        
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