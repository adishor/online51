<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class AdminImageFileType extends FileType
{
    public function getName()
    {
        return 'sonata_admin_image_file';
    }

        public function getParent()
    {
        return 'file';
    }
}

