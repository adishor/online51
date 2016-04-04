<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Ad;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadAdData extends AbstractFixture implements OrderedFixtureInterface
{
    const PATH = 'web/assets/images/publicitate';

    public function load(ObjectManager $manager)
    {

        $ad = new Ad();
        $ad->setTitle('Ad 1');
        copy(self::PATH . '.png', self::PATH . '01.png');
        $file = new UploadedFile(self::PATH . '01.png', 'publicitate01.png', null, null, null, true);
        $ad->setUploadImage($file);

        $ad1 = new Ad();
        $ad1->setTitle('Ad 2');
        copy(self::PATH . '.png', self::PATH . '02.png');
        $file1 = new UploadedFile(self::PATH . '02.png', 'publicitate02.png', null, null, null, true);
        $ad1->setUploadImage($file1);

        $ad2 = new Ad();
        $ad2->setTitle('Ad 3');
        copy(self::PATH . '.png', self::PATH . '03.png');
        $file2 = new UploadedFile(self::PATH . '03.png', 'publicitate03.png', null, null, null, true);
        $ad2->setUploadImage($file2);

        $manager->persist($ad);
        $manager->persist($ad1);
        $manager->persist($ad2);
        $manager->flush();
    }

    public function getOrder()
    {

        return 6;
    }

}