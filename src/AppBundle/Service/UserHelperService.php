<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\User;
use AppBundle\Entity\CreditsUsage;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;
use Application\Sonata\MediaBundle\Entity\Media;

class UserHelperService
{
    protected $entityManager;
    protected $encoderFactory;
    protected $authorizationChecker;
    protected $tokenStorage;
    protected $session;
    protected $translator;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage, Session $session, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function addUserToDatabase($data)
    {
        $user = new User();
        $user->setUsername($data->getEmail());
        $user->setUsernameCanonical($data->getEmail());
        $user->setEmail($data->getEmail());
        $user->setEmailCanonical($data->getEmail());
        $user->setEnabled(false);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setName($data->getName());
        $user->setCompany($data->getCompany());
        $user->setCui($data->getCui());
        $user->setNoRegistrationORC($data->getNoRegistrationORC());
        $user->setNoEmployees($data->getNoEmployees());
        $user->setNoCertifiedEmpowerment($data->getNoCertifiedEmpowerment());
        $user->setIban($data->getIban());
        $user->setBank($data->getBank());
        $user->setPhone($data->getPhone());
        $user->setCounty($data->getCounty());
        $user->setCity($data->getCity());
        $user->setAddress($data->getAddress());
        $mediaId = $this->session->get('tmpMedia');
        if ($mediaId) {
            $user->setImage($this->entityManager->getRepository('ApplicationSonataMediaBundle:Media')->find($mediaId));
            $this->session->remove('tmpMedia');
        }
        $user->setFunction($data->getFunction());
        $user->setConfirmationToken($data->getConfirmationToken());
        $user->addRole(User::ROLE_DEFAULT);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($data->getPassword(), $user->getSalt()));
        $user->setDeleted(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    public function checkCUI($data)
    {
        $data = str_replace('ro', '', strtolower($data));
        $jsonurl = "http://openapi.ro/api/validate/cif/$data.json";
        $json = json_decode(file_get_contents($jsonurl));

        return $json->valid;
    }

    public function checkIBAN($data)
    {
        $jsonurl = "http://openapi.ro/api/validate/iban/$data.json";
        $json = json_decode(file_get_contents($jsonurl));

        return $json->valid;
    }

    public function changePassword($data, $user)
    {
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($data->getPassword(), $user->getSalt()));
        $this->entityManager->flush();
    }

    public function isDomainValidForUser($userId, $domainId)
    {

        if (empty($this->entityManager->getRepository('AppBundle:Order')->findValidUserDomain($userId, $domainId))) {
            return false;
        }
        return true;
    }

    public function getValidUserDocuments($userId, $domainId = null, $subdomainId = null)
    {
        $documents = $this->entityManager->getRepository('AppBundle:CreditsUsage')->findAllValidUserDocuments($userId, $domainId, $subdomainId);
        $validDocuments = array();
        foreach ($documents as $document) {
            $validDocuments[$document['id']] = $document['date'];
        }

        return $validDocuments;
    }

    public function isValidUserDocument($userId, $documentId)
    {
        if (empty($this->entityManager->getRepository('AppBundle:CreditsUsage')->findValidUserDocument($userId, $documentId))) {
            return false;
        }
        return true;
    }

    public function getValidUserVideos($userId, $domainId = null, $subdomainId = null)
    {
        $videos = $this->entityManager->getRepository('AppBundle:CreditsUsage')->findAllValidUserVideos($userId, $domainId, $subdomainId);
        $validVideos = array();
        foreach ($videos as $video) {
            $validVideos[$video['id']] = $video['date'];
        }

        return $validVideos;
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
        if (empty($this->entityManager->getRepository('AppBundle:CreditsUsage')->findValidUserFormularDocument($userId, $documentId))) {
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

    public function updateValidUserCredits()
    {
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
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

    public function getIsUserException()
    {
        return (($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) || ($this->authorizationChecker->isGranted('ROLE_ADMIN')));
    }

    public function checkOldPassword($password, $user)
    {
        if ($user->getPassword() === $this->encoderFactory->getEncoder($user)->encodePassword($password, $user->getSalt())) {

            return true;
        }

        $this->session->getFlashBag()->add('change-password-error', 'form.valid.old-password');

        return false;
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

    public function createUnlockFormularCreditUsage($user, $formular, $formularConfig, $isDraft, $discounted)
    {
        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $creditsUsage->setFormular($formular);
        $creditValue = ($discounted) ? $formular->getDiscountedCreditValue() : $formular->getCreditValue();
        //consumul de credite se realizeaza doar daca configuratia este finala
        if (!$this->getIsUserException() && !$isDraft) {
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
        $creditsUsage->setCredit((!$this->getIsUserException()) ? $creditValue : 0);
        $creditsUsage->setUsageType(CreditsUsage::TYPE_FORMULAR);
        $creditsUsage->setFormConfig(json_encode($formularConfig));
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

    public function isUserAdmin()
    {
        return $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN');
    }

    public function getValidCreditsUsageForMedia($mediaId)
    {
        return $this->entityManager->getRepository('AppBundle:CreditsUsage')
            ->findValidCreditsUsageForMedia($mediaId);
    }

    public function createDemoAccount($email, $name, $demoPassword)
    {
        $user = new User();
        $user->setUsername($email);
        $user->setUsernameCanonical($email);
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setEnabled(true);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setName($name);
        $user->setCompany('');
        $user->setCui(NULL);
        $user->setNoRegistrationORC(NULL);
        $user->setNoCertifiedEmpowerment(NULL);
        $user->setIban(NULL);
        $user->setBank(NULL);
        $user->setPhone(NULL);
        $user->setAddress(NULL);
        $user->setFunction('');
        $user->addRole(User::ROLE_DEFAULT);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($demoPassword, $user->getSalt()));
        $user->setDeleted(false);
        $user->setDemoAccount(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function generateDemoPassword()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    }

}