<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 04/03/2017
 * Time: 12:58
 */

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * FormularCreditsUsage
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EgdFormularCreditsUsageRepository")
 */
class EgdFormularCreditsUsage extends CreditsUsage
{
    /**
     *
     * @var \AppBundle\Entity\File
     * @ORM\ManyToOne(targetEntity="Formular", inversedBy="creditsUsage")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     */
    protected $formular;

    /**
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\EgdFormularConfig", mappedBy="formularCreditsUsage", cascade={"persist"})
     */
    protected $formularConfig;


    /**
     * Set document
     *
     * @param File $file
     * @return CreditsUsage
     * @internal param Document $document
     */
    public function setFormular(\AppBundle\Entity\Formular $file = null)
    {
        $this->formular = $file;

        return $this;
    }

    /**
     * Get document
     *
     * @return \AppBundle\Entity\Document
     */
    public function getFormular()
    {
        return $this->formular;
    }

    /**
     * Set formular config
     *
     * @param \AppBundle\Entity\FormularConfig $formularConfig
     * @return Ad
     */
    public function setFormularConfig(\AppBundle\Entity\EgdFormularConfig $formularConfig)
    {
        $this->formularConfig = $formularConfig;

        return $this;
    }

    /**
     * Get formular config
     *
     * @return \AppBundle\Entity\FormularConfig
     */
    public function getFormularConfig()
    {
        return $this->formularConfig;
    }

    /**
     * @param $currentStepNumber
     */
    public function setCurrentStepNumber($currentStepNumber)
    {
        $formConfig = json_decode($this->getFormularConfig()->getFormConfig());
        if (!isset($formConfig->currentStepNumber) || $formConfig->currentStepNumber < $currentStepNumber) {
            $formConfig->currentStepNumber = $currentStepNumber;
        }

        $this->getFormularConfig()->setFormConfig(json_encode($formConfig));
    }


    public function getCurrentStepNumber()
    {
        $formConfig = json_decode($this->getFormularConfig()->getFormConfig());
        if (isset($formConfig->currentStepNumber)) {
            return $formConfig->currentStepNumber;
        }

        return null;
    }

}