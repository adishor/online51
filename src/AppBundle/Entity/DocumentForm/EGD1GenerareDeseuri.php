<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGD1GenerareDeseuri
{
    /**
     *
     * @var type
     *
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $luna;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $cantitateDeseuGenerate;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $cantitateDeseuValorificata;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $cantitateDeseuEliminata;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $cantitateDeseuInStoc;

    function getLuna()
    {
        return $this->luna;
    }

    function getCantitateDeseuGenerate()
    {
        return $this->cantitateDeseuGenerate;
    }

    function getCantitateDeseuValorificata()
    {
        return $this->cantitateDeseuValorificata;
    }

    function getCantitateDeseuEliminata()
    {
        return $this->cantitateDeseuEliminata;
    }

    function getCantitateDeseuInStoc()
    {
        return $this->cantitateDeseuInStoc;
    }

    function setLuna($luna)
    {
        $this->luna = $luna;
    }

    function setCantitateDeseuGenerate($cantitateDeseuGenerate)
    {
        $this->cantitateDeseuGenerate = $cantitateDeseuGenerate;
    }

    function setCantitateDeseuValorificata($cantitateDeseuValorificata)
    {
        $this->cantitateDeseuValorificata = $cantitateDeseuValorificata;
    }

    function setCantitateDeseuEliminata($cantitateDeseuEliminata)
    {
        $this->cantitateDeseuEliminata = $cantitateDeseuEliminata;
    }

    function setCantitateDeseuInStoc($cantitateDeseuInStoc)
    {
        $this->cantitateDeseuInStoc = $cantitateDeseuInStoc;
    }

}