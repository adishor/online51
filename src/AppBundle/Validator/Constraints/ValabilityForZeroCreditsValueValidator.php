<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValabilityForZeroCreditsValueValidator extends ConstraintValidator
{

    public function validate($document, Constraint $constraint)
    {
        if (($document->getCreditValue() > 0) && ($document->getValabilityDays() == NULL)) {
            $this->context->addViolation($constraint->message, array(), null);
        }
    }

}