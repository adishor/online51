<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class UserHelperService
{
    protected $entityManager;
    protected $encoderFactory;
    protected $authorizationChecker;
    protected $tokenStorage;
    protected $session;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory, AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage, Session $session)
    {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
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
        $user->setUploadImage($data->getUploadImage());
        $user->setFunction($data->getFunction());
        $user->setConfirmationToken($data->getConfirmationToken());
        $user->addRole(User::ROLE_DEFAULT);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($data->getPassword(), $user->getSalt()));
        $user->setDeleted(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
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

    public function getValidUserDocuments($userId, $domainId = null)
    {
        $documents = $this->entityManager->getRepository('AppBundle:CreditsUsage')->findAllValidUserDocuments($userId, $domainId);
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

    public function updateValidUserCredits()
    {
        $userId = $this->tokenStorage->getToken()->getUser()->getId();
        $user = $this->entityManager->find('ApplicationSonataUserBundle:User', $userId);
        $userCredits = $user->getCreditsTotal();
        $orderRepository = $this->entityManager->getRepository('AppBundle:Order');
        $validBeforeCredits = $orderRepository->findValidUserCredits($userId, $user->getLastCreditUpdate());
        $validNowCredits = $orderRepository->findValidUserCredits($userId);
        if (null !== $userCredits) {
            $user->setCreditsTotal(min($userCredits, $validBeforeCredits) + min(0, ($validNowCredits - $validBeforeCredits)));
            $user->setLastCreditUpdate(new \DateTime());
        }
        $this->entityManager->flush();
    }

    public function getIsUserException()
    {
        return $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN');
    }

    public function checkOldPassword($password, $user)
    {
        if ($user->getPassword() === $this->encoderFactory->getEncoder($user)->encodePassword($password, $user->getSalt())) {

            return true;
        }

        $this->session->getFlashBag()->add('change-password-error', 'form.valid.old-password');

        return false;
    }

}