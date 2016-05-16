<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValabilityFormularValidator extends ConstraintValidator
{

    public function validate($formular, Constraint $constraint)
    {
        if ((NULL !== $formular->getValabilityDays()) && (NULL !== $formular->getValabilityMonth())) {
            $this->context->addViolation($constraint->message, array(), null);
        }

        if ((NULL === $formular->getValabilityDays()) && (NULL === $formular->getValabilityMonth())) {
            $this->context->addViolation($constraint->messageNULL, array(), null);
        }
    }

}