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
     * @Assert\Type(type="double", groups={"generateDocument", "button2"})
     * @Assert\Range(min = 0, groups={"generateDocument", "button2"})
     */
    protected $cantitateDeseuGenerate;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     * @Assert\Type(type="double", groups={"generateDocument", "button2"})
     * @Assert\Range(min = 0, groups={"generateDocument", "button2"})
     */
    protected $cantitateDeseuValorificata;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     * @Assert\Type(type="double", groups={"generateDocument", "button2"})
     * @Assert\Range(min = 0, groups={"generateDocument", "button2"})
     */
    protected $cantitateDeseuEliminata;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     * @Assert\Type(type="double", groups={"generateDocument", "button2"})
     * @Assert\Range(min = 0, groups={"generateDocument", "button2"})
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