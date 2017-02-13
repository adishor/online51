<?php

namespace AppBundle\Service\DocumentForm\Base;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

abstract class FormularGenericUnique extends FormularGeneric
{
    public function __construct(EntityManager $entityManager, Serializer $jmsSerializer, $fileLocator)
    {
        parent::__construct($entityManager, $jmsSerializer, $fileLocator);

        $this->setUnique();
    }

    abstract public function getUniquenessValues(Formular $formular);
}
