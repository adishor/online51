<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Ad;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadAdData extends AbstractFixture implements OrderedFixtureInterface
{
    const PATH = 'web/assets/images/publicitate.png';
    const FILENAME = 'publicitate.png';

    public function load(ObjectManager $manager)
    {

        $ad = new Ad();
        $ad->setName('Ad 1');
        $file = new UploadedFile($this::PATH, $this::FILENAME);
        $media = new Media();
        $media->setBinaryContent($file);
        $media->setName($this::FILENAME);
        $media->setProviderName('sonata.media.provider.image');
        $media->setContext('default');
        $media->setMediaType($media::IMAGE_TYPE);
        $ad->setImage($media);


        $ad1 = new Ad();
        $ad1->setName('Ad 2');
        $media1 = new Media();
        $media1->setBinaryContent($file);
        $media1->setName($this::FILENAME);
        $media1->setProviderName('sonata.media.provider.image');
        $media1->setContext('default');
        $media1->setMediaType($media1::IMAGE_TYPE);
        $ad1->setImage($media1);


        $ad2 = new Ad();
        $ad2->setName('Ad 3');
        $media2 = new Media();
        $media2->setBinaryContent($file);
        $media2->setName($this::FILENAME);
        $media2->setProviderName('sonata.media.provider.image');
        $media2->setContext('default');
        $media2->setMediaType($media2::IMAGE_TYPE);
        $ad2->setImage($media2);

        $manager->persist($ad);
        $manager->persist($ad1);
        $manager->persist($ad2);
        $manager->persist($media);
        $manager->persist($media1);
        $manager->persist($media2);

        $manager->flush();
    }

    public function getOrder()
    {

        return 7;
    }

}