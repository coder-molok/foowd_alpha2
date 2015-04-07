<?php

namespace Propel\Runtime\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class PropelFoowd extends Constraint{

    public $message = '';
    public $column = '';
    public $type = null;
    public $list = null;

}