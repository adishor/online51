<?php

namespace AppBundle\Service\DocumentForm\Base;

use AppBundle\Entity\CreditsUsage;
use AppBundle\Entity\FormularCreditsUsage;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

abstract class FormularGeneric
{
    protected $unique;
    protected $hasController;

    protected $jmsSerializer;
    protected $entityManager;
    protected $fileLocator;

    public function __construct(EntityManager $entityManager, Serializer $jmsSerializer, $fileLocator)
    {
        $this->entityManager = $entityManager;
        $this->jmsSerializer = $jmsSerializer;
        $this->fileLocator = $fileLocator;

        $this->unique = false;
        $this->hasController = false;
    }

    public function hasToBeUnique()
    {
        return $this->unique;
    }

    public function hasController()
    {
        return $this->hasController;
    }

    public function setUnique()
    {
        $this->unique = true;
    }

    public function setHasController()
    {
        $this->hasController = true;
    }

    public function checkValidity($user, $creditsUsage)
    {
        if ($creditsUsage->getUser()->getId() !== $user->getId()) {
            return 'formular-documents.access-denied';
        }

        if (null === $creditsUsage->getFormular()) {
            return 'formular-documents.access-denied';
        }

        if (null !== $creditsUsage->getMedia()) {
           return 'formular-documents.access-denied';
        }

        return false;
    }

    abstract function getEntity();

    abstract function applyDefaultFormData(FormularCreditsUsage $creditsUsage, $user);

}