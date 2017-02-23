<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class FormularConfig
{

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $formConfig;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $isFormConfigFinished;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $formData;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $formHash;


    /**
     **
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\FormularCreditsUsage", inversedBy="formularConfig", cascade={"persist"})
     * @ORM\JoinColumn(name="credits_usage_id", referencedColumnName="id")
     */
    private $formularCreditsUsage;

    /**
     * Set formConfig
     *
     * @param array $formConfig
     * @return CreditsUsage
     */
    public function setFormConfig($formConfig)
    {
        $this->formConfig = $formConfig;

        return $this;
    }

    /**
     * Get formConfig
     *
     * @return array
     */
    public function getFormConfig()
    {
        return $this->formConfig;
    }

    /**
     * Set isFormConfigFinished
     *
     * @param boolean $isFormConfigFinished
     * @return CreditsUsage
     */
    public function setIsFormConfigFinished($isFormConfigFinished)
    {
        $this->isFormConfigFinished = $isFormConfigFinished;

        return $this;
    }

    /**
     * Get isFormConfigFinished
     *
     * @return boolean
     */
    public function getIsFormConfigFinished()
    {
        return $this->isFormConfigFinished;
    }

    /**
     * Set formData
     *
     * @param array $formData
     * @return CreditsUsage
     */
    public function setFormData($formData)
    {
        $this->formData = $formData;

        return $this;
    }

    /**
     * Get formData
     *
     * @return array
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * Set formHash
     *
     * @param string $formHash
     * @return CreditsUsage
     */
    public function setFormHash($formHash)
    {
        $this->formHash = $formHash;

        return $this;
    }

    /**
     * Get formHash
     *
     * @return string
     */
    public function getFormHash()
    {
        return $this->formHash;
    }

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
