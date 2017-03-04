<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 04/03/2017
 * Time: 13:11
 */

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * OtherFormularConfig
 *
 * @ORM\Entity()
 */
class FormularConfig extends Config
{
    /**
     **
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\FormularCreditsUsage", inversedBy="formularConfig", cascade={"persist"})
     * @ORM\JoinColumn(name="credits_usage_id", referencedColumnName="id")
     */
    private $formularCreditsUsage;

    /**
     * Set formular config
     *
     * @param FormularConfig|FormularCreditsUsage $formularConfig
     * @return Ad
     */
    public function setFormularCreditsUsage(\AppBundle\Entity\FormularCreditsUsage $formularConfig)
    {
        $this->formularCreditsUsage = $formularConfig;

        return $this;
    }

    /**
     * Get formular config
     *
     * @return \AppBundle\Entity\FormularConfig
     */
    public function getFormularCreditsUsage()
    {
        return $this->formularCreditsUsage;
    }
}