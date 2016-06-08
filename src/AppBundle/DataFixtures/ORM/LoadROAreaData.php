<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\ROArea;

class LoadROAreaData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $countyRepository = $manager->getRepository('AppBundle:ROCounty');

        $area = new ROArea();
        $area->setName('Nord-Est');
        $area->addCounty($countyRepository->find(4));
        $area->addCounty($countyRepository->find(7));
        $area->addCounty($countyRepository->find(24));
        $area->addCounty($countyRepository->find(29));
        $area->addCounty($countyRepository->find(35));
        $area->addCounty($countyRepository->find(40));
        $area->setDeleted(FALSE);

        $area1 = new ROArea();
        $area1->setName('Sud-Est');
        $area1->addCounty($countyRepository->find(8));
        $area1->addCounty($countyRepository->find(10));
        $area1->addCounty($countyRepository->find(14));
        $area1->addCounty($countyRepository->find(18));
        $area1->addCounty($countyRepository->find(38));
        $area1->addCounty($countyRepository->find(41));
        $area1->setDeleted(FALSE);

        $area2 = new ROArea();
        $area2->setName('Sud-Muntenia');
        $area2->addCounty($countyRepository->find(3));
        $area2->addCounty($countyRepository->find(11));
        $area2->addCounty($countyRepository->find(16));
        $area2->addCounty($countyRepository->find(19));
        $area2->addCounty($countyRepository->find(23));
        $area2->addCounty($countyRepository->find(31));
        $area2->addCounty($countyRepository->find(36));
        $area2->setDeleted(FALSE);

        $area3 = new ROArea();
        $area3->setName('Sud-Vest Oltenia');
        $area3->addCounty($countyRepository->find(17));
        $area3->addCounty($countyRepository->find(20));
        $area3->addCounty($countyRepository->find(27));
        $area3->addCounty($countyRepository->find(30));
        $area3->addCounty($countyRepository->find(39));
        $area3->setDeleted(FALSE);

        $area4 = new ROArea();
        $area4->setName('Vest');
        $area4->addCounty($countyRepository->find(2));
        $area4->addCounty($countyRepository->find(12));
        $area4->addCounty($countyRepository->find(22));
        $area4->addCounty($countyRepository->find(37));
        $area4->setDeleted(FALSE);

        $area5 = new ROArea();
        $area5->setName('Nord-Vest');
        $area5->addCounty($countyRepository->find(5));
        $area5->addCounty($countyRepository->find(6));
        $area5->addCounty($countyRepository->find(13));
        $area5->addCounty($countyRepository->find(26));
        $area5->addCounty($countyRepository->find(33));
        $area5->addCounty($countyRepository->find(32));
        $area5->setDeleted(FALSE);

        $area6 = new ROArea();
        $area6->setName('Centru');
        $area6->addCounty($countyRepository->find(1));
        $area6->addCounty($countyRepository->find(9));
        $area6->addCounty($countyRepository->find(15));
        $area6->addCounty($countyRepository->find(21));
        $area6->addCounty($countyRepository->find(28));
        $area6->addCounty($countyRepository->find(34));
        $area6->setDeleted(FALSE);

        $area7 = new ROArea();
        $area7->setName('Bucuresti-Ilfov');
        $area7->addCounty($countyRepository->find(53));
        $area7->addCounty($countyRepository->find(25));
        $area7->setDeleted(FALSE);

        $manager->persist($area);
        $manager->persist($area1);
        $manager->persist($area2);
        $manager->persist($area3);
        $manager->persist($area4);
        $manager->persist($area5);
        $manager->persist($area6);
        $manager->persist($area7);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }

}