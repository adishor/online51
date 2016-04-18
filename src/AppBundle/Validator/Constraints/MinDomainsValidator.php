<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MinDomainsValidator extends ConstraintValidator
{

    public function validate($subscription, Constraint $constraint)
    {
        if (count($subscription->getDomains()) < $subscription->getDomainAmount()) {
            $this->context->addViolationAt('domains', $constraint->message, array(), null);
        }
    }

}