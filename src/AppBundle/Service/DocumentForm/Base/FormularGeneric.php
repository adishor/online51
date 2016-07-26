<?php

namespace AppBundle\Service\DocumentForm\Base;

use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

class FormularGeneric
{
    protected $slug;

    protected $name;

    protected $jmsSerializer;

    protected $entityManager;

    protected $kernelRootDir;

    public function __construct(EntityManager $entityManager, Serializer $jmsSerializer, $kernelRootDir)
    {
        $this->entityManager = $entityManager;
        $this->jmsSerializer = $jmsSerializer;
        $this->kernelRootDir = $kernelRootDir;
    }

    public function setName($slug)
    {
        $this->slug = $slug;
        $this->name = str_replace("_", "", $slug);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getEntity()
    {
        if (!$this->name) {
            throw new Exception('Please set a value for name variable using setName function.');
        }

        return "AppBundle\\Entity\\DocumentForm\\" . $this->name . "\\" . $this->name;
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

}