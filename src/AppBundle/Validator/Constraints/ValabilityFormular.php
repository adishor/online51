<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValabilityFormular extends Constraint
{
    public $message = 'assert.valability-formular';
    public $messageNULL = 'assert.valability-formular-null';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}