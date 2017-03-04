<?php

namespace AppBundle\Service;

use AppBundle\Document\EvidentaGestiuniiDeseurilor\EvidentaGestiuniiDeseurilor;
use AppBundle\Entity\CreditsUsage;
use AppBundle\Entity\EgdFormularConfig;
use AppBundle\Entity\EgdFormularCreditsUsage;
use AppBundle\Entity\Formular;
use AppBundle\Entity\FormularConfig;
use AppBundle\Entity\FormularCreditsUsage;
use AppBundle\Entity\OtherFormularConfig;
use AppBundle\Entity\Video;
use AppBundle\Entity\VideoCreditsUsage;
use AppBundle\EventListener\Event\CreditUsedEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;
use AppBundle\Service\UserService;

class CreditsUsageService
{
    protected $entityManager;
    protected $translator;
    protected $userService;
    protected $eventDispatcher;

    public function __construct(EntityManager $entityManager, TranslatorInterface $translator, UserService $userService, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->userService = $userService;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createUnlockDocumentCreditUsage($user, $document)
    {
        $creditsUsage = new DocumentCreditsUsage();
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

    public function createUnlockVideoCreditUsage($user, Video $video)
    {
        $creditValue = $video->getCreditValue();

        $creditsUsage = new VideoCreditsUsage();
        $creditsUsage->setUser($user);
        $creditsUsage->setFile($video);
        $creditsUsage->setMentions($this->translator->trans('credit-usage.video-unlocked-by-user'));
        $expireDate = new \DateTime();
        $expireDate->add(new \DateInterval('P' . $creditsUsage->getFile()->getValabilityDays() . 'D'));
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit($creditValue);
        $creditsUsage->setMedia($video->getMedia());

        $this->entityManager->persist($creditsUsage);
        $this->entityManager->flush();

        $event = new CreditUsedEvent($video);
        $this->eventDispatcher->dispatch('app.event.credits_used', $event);
    }

    public function createUnlockFormularCreditUsage($user, Formular $formular, $formularConfigArray, $discounted, $formularData)
    {
        if ("evidenta_gestiunii_deseurilor" === $formular->getSlug()) {
            $creditsUsage = new EgdFormularCreditsUsage();
        } else {
            $creditsUsage = new FormularCreditsUsage();
        }

        $creditValue = ($discounted) ? $formular->getDiscountedCreditValue() : $formular->getCreditValue();
        $creditsUsage->setMentions($this->translator->trans('credit-usage.formular-unlocked-by-user'));

        if (null !== $formular->getValabilityDays()) {
            $expireDate = new \DateTime();
            $expireDate->add(new \DateInterval('P' . $formular->getValabilityDays() . 'D'));
        }

        if ("evidenta_gestiunii_deseurilor" === $formular->getSlug()) {
            $formularConfigArray['an'] = !$discounted ?: $formularConfigArray['an'] + 1;
            $expireYear = $formularConfigArray['an'] + 1;
            $timestamp = strtotime($expireYear . '-' . EvidentaGestiuniiDeseurilor::$startMonth . '-01 23:59:59');
            $expireDate = new \DateTime(date('Y-m-t H:i:s', $timestamp));
        }

        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit((!$this->userService->getIsUserException()) ? $creditValue : 0);

        $creditsUsage->setUser($user);
        $creditsUsage->setFormular($formular);

        if ("evidenta_gestiunii_deseurilor" === $formular->getSlug()) {
            $formularConfig = new EgdFormularConfig();
            $formularConfig->setYear($formularConfigArray['an']);
            $formularConfig->setCode($formularConfigArray['tip_deseu']);
        } else {
            $formularConfig = new FormularConfig();
        }

        $formularConfig->setFormConfig(json_encode($formularConfigArray));
        $formularConfig->setFormData($formularData);
        $formularConfig->setFormHash(md5(json_encode($user->getId()) . json_encode($formularConfig)));
        $formularConfig->setIsFormConfigFinished(false);
        $formularConfig->setFormularCreditsUsage($creditsUsage);

        $this->entityManager->persist($creditsUsage);
        $this->entityManager->persist($formularConfig);
        $this->entityManager->flush();

        $event = new CreditUsedEvent($formular);
        $this->eventDispatcher->dispatch('app.event.credits_used', $event);

        return $creditsUsage->getId();
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