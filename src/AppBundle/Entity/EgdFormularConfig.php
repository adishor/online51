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
 * EgdFormularConfig
 *
 * @ORM\Entity()
 */
class EgdFormularConfig extends Config
{
    /**
     *
     * @ORM\Column(type="string")
     */
    private $year;

    /**
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     *
     * @ORM\Column(type="string")
     */
    private $step;


    /**
     **
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\EgdFormularCreditsUsage", inversedBy="formularConfig", cascade={"persist"})
     * @ORM\JoinColumn(name="credits_usage_id", referencedColumnName="id")
     */
    private $formularCreditsUsage;

    /**
     * Set formular config
     *
     * @param FormularConfig|FormularCreditsUsage $formularConfig
     * @return Ad
     */
    public function setFormularCreditsUsage(\AppBundle\Entity\EgdFormularCreditsUsage $formularConfig)
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

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param mixed $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }
}