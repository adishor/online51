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

        $formular4 = new Formular();
        $formular4->setName('Decizie Comisie Cercetare Accidente');
        $formular4->setCreditValue(0);

        $formular5 = new Formular();
        $formular5->setName('Decizie Organizare Activitate SSM');
        $formular5->setCreditValue(0);

        $formular6 = new Formular();
        $formular6->setName('Proces Verbal Sedinta CSSM');
        $formular6->setCreditValue(0);

        $formular7 = new Formular();
        $formular7->setName('Proces Verbal Control');
        $formular7->setCreditValue(0);

        $formular8 = new Formular();
        $formular8->setName('Permis De Lucru Cu Foc');
        $formular8->setCreditValue(0);

        $formular9 = new Formular();
        $formular9->setName('Decizie Personal Cu Atributii PSI');
        $formular9->setCreditValue(0);

        $manager->persist($formular);
        $manager->persist($formular1);
        $manager->persist($formular2);
        $manager->persist($formular3);
        $manager->persist($formular4);
        $manager->persist($formular5);
        $manager->persist($formular6);
        $manager->persist($formular7);
        $manager->persist($formular8);
        $manager->persist($formular9);
        $manager->flush();
    }

    public function getOrder()
    {

        return 7;
    }

}