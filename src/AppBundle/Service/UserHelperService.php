<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserHelperService
{
    protected $entityManager;
    protected $encoderFactory;
    protected $authorizationChecker;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function addUserToDatabase($data)
    {
        $user = new User();
        $user->setUsername($data->getEmail());
        $user->setUsernameCanonical($data->getEmail());
        $user->setEmail($data->getEmail());
        $user->setEmailCanonical($data->getEmail());
        $user->setEnabled(true);
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
        $user->addRole(User::ROLE_DEFAULT);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($data->getPassword(), $user->getSalt()));
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

    public function changePassword($data, $token)
    {
        $user = $this->entityManager->getRepository('ApplicationSonataUserBundle:User')->findOneByConfirmationToken($token);
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
        array_walk_recursive($documents, function($v, $k) use (&$validDocuments) {
            $validDocuments[$v] = true;
        });

        return $validDocuments;
    }

    public function getValidUserCredits($userId)
    {

        return $this->entityManager->getRepository('AppBundle:Order')->findValidUserCredits($userId);
    }

    public function getIsUserException()
    {
        return $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN');
    }

}