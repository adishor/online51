<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadROCountyCityData extends AbstractFixture implements OrderedFixtureInterface
{
    private $filename = 'app/Resources/sql/county-city.sql';

    public function load(ObjectManager $manager)
    {
        $sql = file_get_contents($this->filename);
        $manager->getConnection()->exec($sql);
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}

