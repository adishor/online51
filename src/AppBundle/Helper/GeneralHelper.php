<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 08/02/2017
 * Time: 18:01
 */

namespace AppBundle\Helper;


use Doctrine\Common\Util\Inflector;

class GeneralHelper
{
    public static function getServiceIdBySlug($slug)
    {
        $formularId = Inflector::tableize(Inflector::camelize($slug));

        return $formularId;
    }
}