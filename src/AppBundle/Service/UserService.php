<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Application\Sonata\UserBundle\Entity\User;
use AppBundle\Entity\Profile;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

class UserService
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
        $user->setConfirmationToken($data->getConfirmationToken());
        $user->addRole(User::ROLE_DEFAULT);
        $user->setPlainPassword($data->getPassword());
        //$user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($data->getPassword(), $user->getSalt()));
        $user->setDeleted(false);


        $profile = new Profile();
        $profile->setName($data->getProfile()->getName());
        $profile->setCompany($data->getProfile()->getCompany());
        $profile->setPhone($data->getProfile()->getPhone());
        $profile->setCui($data->getProfile()->getCui());
        $profile->setNoRegistrationORC($data->getProfile()->getNoRegistrationORC());
        $profile->setNoEmployees($data->getProfile()->getNoEmployees());
        $profile->setNoCertifiedEmpowerment($data->getProfile()->getNoCertifiedEmpowerment());
        $profile->setIban($data->getProfile()->getIban());
        $profile->setBank($data->getProfile()->getBank());
        $profile->setCounty($data->getProfile()->getCounty());
        $profile->setCity($data->getProfile()->getCity());
        $profile->setAddress($data->getProfile()->getAddress());
        $profile->setAccountType(0);
        $mediaId = $this->session->get('tmpMedia');
        if ($mediaId) {
            $profile->setImage($this->entityManager->getRepository('ApplicationSonataMediaBundle:Media')->find($mediaId));
            $this->session->remove('tmpMedia');
        }
        $profile->setFunction($data->getProfile()->getFunction());
        $user->setProfile($profile);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    public function changePassword($data, $user)
    {
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($data->getPassword(), $user->getSalt()));
        $this->entityManager->flush();
    }

    public function checkOldPassword($password, $user)
    {
        if ($user->getPassword() === $this->encoderFactory->getEncoder($user)->encodePassword($password, $user->getSalt())) {
            return true;
        }

        $this->session->getFlashBag()->add('change-password-error', 'form.valid.old-password');
        return false;
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
        $user->addRole(User::ROLE_DEFAULT);
        $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($demoPassword, $user->getSalt()));

        $profile = new Profile();
        $profile->setName($name);
        $profile->setCompany('');
        $profile->setCui(NULL);
        $profile->setNoRegistrationORC(NULL);
        $profile->setNoCertifiedEmpowerment(NULL);
        $profile->setIban(NULL);
        $profile->setBank(NULL);
        $profile->setPhone(NULL);
        $profile->setAddress(NULL);
        $profile->setFunction(NULL);
        $profile->setDemoAccount(true);
        $profile->setAccountType(0);
        $user->setProfile($profile);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getIsUserException()
    {
        return (($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) ||
          ($this->authorizationChecker->isGranted('ROLE_ADMIN')));
    }

    public function getLoggedUserId()
    {
        return $this->tokenStorage->getToken()->getUser()->getId();
    }

    public function isUserAdmin()
    {
        return $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN');
    }

    public function isDomainValidForUser($userId, $domainId)
    {

        if (empty($this->entityManager->getRepository('AppBundle:Order')->findValidUserDomain($userId, $domainId))) {
            return false;
        }
        return true;
    }
}
