<?php

namespace AppBundle\Service;

use AppBundle\Entity\CreditsUsage;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use AppBundle\Service\UserService;

class CreditsUsageService
{
    protected $entityManager;
    protected $translator;
    protected $userService;

    public function __construct(EntityManager $entityManager, TranslatorInterface $translator, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->userService = $userService;
    }

    public function createUnlockDocumentCreditUsage($user, $document)
    {
        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $creditsUsage->setDocument($document);
        $user->setCreditsTotal($user->getCreditsTotal() - $document->getCreditValue());
        $user->setLastCreditUpdate(new \DateTime());
        $creditsUsage->setMentions($this->translator->trans('credit-usage.document-unlocked-by-user'));
        $expireDate = new \DateTime();
        $expireDate->add(new \DateInterval('P' . $creditsUsage->getDocument()->getValabilityDays() . 'D'));
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit($document->getCreditValue());
        $creditsUsage->setUsageType(CreditsUsage::TYPE_DOCUMENT);
        $creditsUsage->setMedia($document->getMedia());
        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();
    }

    public function createUnlockVideoCreditUsage($user, $video)
    {
        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $creditsUsage->setVideo($video);
        $user->setCreditsTotal($user->getCreditsTotal() - $video->getCreditValue());
        $user->setLastCreditUpdate(new \DateTime());
        $creditsUsage->setMentions($this->translator->trans('credit-usage.video-unlocked-by-user'));
        $expireDate = new \DateTime();
        $expireDate->add(new \DateInterval('P' . $creditsUsage->getVideo()->getValabilityDays() . 'D'));
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit($video->getCreditValue());
        $creditsUsage->setUsageType(CreditsUsage::TYPE_VIDEO);
        $creditsUsage->setMedia($video->getMedia());
        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();
    }

    public function createUnlockFormularCreditUsage($user, $formular, $formularConfig, $isDraft, $discounted, $formularData)
    {
        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $creditsUsage->setFormular($formular);
        $creditValue = ($discounted) ? $formular->getDiscountedCreditValue() : $formular->getCreditValue();
        //consumul de credite se realizeaza doar daca configuratia este finala
        if (!$this->userService->getIsUserException() && !$isDraft) {
            $user->setCreditsTotal($user->getCreditsTotal() - $creditValue);
            $user->setLastCreditUpdate(new \DateTime());
        }
        $creditsUsage->setMentions($this->translator->trans('credit-usage.formular-unlocked-by-user'));
        if (null !== $formular->getValabilityDays()) {
            $expireDate = new \DateTime();
            $expireDate->add(new \DateInterval('P' . $formular->getValabilityDays() . 'D'));
        }
        if (null !== $formular->getValabilityMonth()) {
            $an = ((isset($formularConfig['an'])) ? $formularConfig['an'] : date('Y')) + 1;
            $timestamp = strtotime($an . '-' . $formular->getValabilityMonth() . '-01 23:59:59');
            $expireDate = new \DateTime(date('Y-m-t H:i:s', $timestamp));
        }
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit((!$this->userService->getIsUserException()) ? $creditValue : 0);
        $creditsUsage->setUsageType(CreditsUsage::TYPE_FORMULAR);
        $creditsUsage->setFormConfig(json_encode($formularConfig));
        $creditsUsage->setFormData($formularData);
        $creditsUsage->setFormHash(md5(json_encode($user->getId()) . json_encode($formularConfig)));
        $creditsUsage->setIsFormConfigFinished(!$isDraft);
        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();

        return $creditsUsage->getId();
    }

    public function createExpiredCreditUsage($user, $credit)
    {
        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $user->setLastCreditUpdate(new \DateTime());
        $creditsUsage->setMentions($this->translator->trans('credit-usage.expired'));
        $expireDate = new \DateTime();
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit($credit);
        $creditsUsage->setUsageType(CreditsUsage::TYPE_EXPIRED);
        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();
    }

    public function isValidUserDocument($userId, $documentId)
    {
        if (empty($this->entityManager->getRepository('AppBundle:CreditsUsage')->findValidUserDocument($userId, $documentId))) {
            return false;
        }
        return true;
    }

    public function isValidUserVideo($userId, $videoId)
    {
        if (empty($this->entityManager->getRepository('AppBundle:CreditsUsage')->findValidUserVideo($userId, $videoId))) {
            return false;
        }
        return true;
    }

    public function isValidUserFormularDocument($userId, $documentId)
    {
        if (empty($this->entityManager->getRepository('AppBundle:CreditsUsage')
              ->findValidUserFormularDocument($userId, $documentId))) {
            return false;
        }
        return true;
    }

    public function isValidUserFormular($userId, $formularId, $formularConfig)
    {
        if (empty($this->entityManager->getRepository('AppBundle:CreditsUsage')
              ->findValidUserFormular($userId, $formularId, $formularConfig))) {
            return false;
        }
        return true;
    }

    public function getValidUserDocuments($userId, $domainId = null, $subdomainId = null)
    {
        $documents = $this->entityManager->getRepository('AppBundle:CreditsUsage')
          ->findAllValidUserDocuments($userId, $domainId, $subdomainId);
        $documentsZeroCreditValue = $this->entityManager->getRepository('AppBundle:Document')
          ->findAllZeroCreditValueDocuments($domainId, $subdomainId);
        $validDocuments = array();
        foreach ($documents as $document) {
            $validDocuments[$document['id']] = $document['date'];
        }
        foreach ($documentsZeroCreditValue as $document) {
            $validDocuments[$document['id']] = 'FREE';
        }

        return $validDocuments;
    }

    public function getValidUserVideos($userId, $domainId = null, $subdomainId = null)
    {
        $videos = $this->entityManager->getRepository('AppBundle:CreditsUsage')
          ->findAllValidUserVideos($userId, $domainId, $subdomainId);
        $videosZeroCreditValue = $this->entityManager->getRepository('AppBundle:Video')
          ->findAllZeroCreditValueVideos($domainId, $subdomainId);
        $validVideos = array();
        foreach ($videos as $video) {
            $validVideos[$video['id']] = $video['date'];
        }
        foreach ($videosZeroCreditValue as $video) {
            $validVideos[$video['id']] = 'FREE';
        }

        return $validVideos;
    }

    public function updateValidUserCredits()
    {
        $userId = $this->userService->getLoggedUserId();
        $user = $this->entityManager->find('ApplicationSonataUserBundle:User', $userId);
        $userCredits = $user->getCreditsTotal();
        $orderRepository = $this->entityManager->getRepository('AppBundle:Order');
        $validBeforeCredits = $orderRepository->findValidUserCredits($userId, $user->getLastCreditUpdate());
        $validNowCredits = $orderRepository->findValidUserCredits($userId);
        if (null !== $userCredits) {
            $updatedCredits = min($userCredits, $validBeforeCredits) + ($validNowCredits - $validBeforeCredits);
            $user->setCreditsTotal($updatedCredits);
            $user->setLastCreditUpdate(new \DateTime());
            if ($userCredits > $updatedCredits) {
                $this->createExpiredCreditUsage($user, $userCredits - $updatedCredits);
            }
        }
        $this->entityManager->flush();
    }

}