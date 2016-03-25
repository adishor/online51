<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('superadmin');
        $user->setUsernameCanonical('superadmin');
        $user->setFirstname('superadmin');
        $user->setLastname('superadmin');
        $user->setEmail('amoraru@pitechplus.com');
        $user->setEmailCanonical('amoraru@pitechplus.com');
        $user->setEnabled(true);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setName('SuperAdmin');
        $user->setCompany('PITECH+PLUS');
        $user->addRole(User::ROLE_SUPER_ADMIN);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('admin', $user->getSalt()));

        $manager->persist($user);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}

