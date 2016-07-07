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

        $formular1 = new Formular();
        $formular1->setName('Convocator CSSM');
        $formular1->setCreditValue(0);

        $formular2 = new Formular();
        $formular2->setName('Decizie Componenta CSSM');
        $formular2->setCreditValue(0);

        $formular3 = new Formular();
        $formular3->setName('Decizie Personal Cu Atributii');
        $formular3->setCreditValue(0);

        $manager->persist($formular);
        $manager->persist($formular1);
        $manager->persist($formular2);
        $manager->persist($formular3);
        $manager->flush();
    }

    public function getOrder()
    {

        return 7;
    }

}