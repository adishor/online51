<?php

namespace AppBundle\Util;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class FileNamer implements NamerInterface
{

    public function name($object, PropertyMapping $mapping)
    {
        return uniqid() . $object->getUploadImage()->getClientOriginalName();
    }

}