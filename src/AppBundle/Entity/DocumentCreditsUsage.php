<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 13/02/2017
 * Time: 20:15
 */

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * @ORM\Entity()
 */

class DocumentCreditsUsage extends CreditsUsage
{

}