<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class DifferentEmailValidator extends ConstraintValidator
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($user, Constraint $constraint)
    {
        if (!empty($this->entityManager->getRepository('ApplicationSonataUserBundle:User')->findBy(array('email' => $user->getEmail(), 'deleted' => false)))) {
            $this->context->addViolationAt('email', $constraint->message, array(), null);
        }
    }

}