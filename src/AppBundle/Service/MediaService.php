<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class MediaService
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getZeroCreditValueDocumentForMedia($mediaId)
    {
        return $this->entityManager->getRepository('AppBundle:Document')
            ->findZeroCreditValueDocumentForMedia($mediaId);
    }

    public function getZeroCreditValueVideoForMedia($mediaId)
    {
        return $this->entityManager->getRepository('AppBundle:Video')
            ->findZeroCreditValueVideoForMedia($mediaId);
    }

    public function getValidCreditsUsageForMedia($mediaId)
    {
        return $this->entityManager->getRepository('AppBundle:CreditsUsage')
            ->findValidCreditsUsageForMedia($mediaId);
    }

}