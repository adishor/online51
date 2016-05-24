<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Formular;

class LoadDocumentFormData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $formular = new Formular();
        $formular->setName('Evidenta Gestiunii Deseurilor');
        $formular->setCreditValue(0);

        $manager->persist($formular);
        $manager->flush();
    }

    public function getOrder()
    {

        return 7;
    }

}