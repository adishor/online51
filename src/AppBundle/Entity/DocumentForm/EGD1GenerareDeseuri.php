<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;

class EGD1GenerareDeseuri
{
    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $luna;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $cantitateDeseuGenerate;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $cantitateDeseuValorificata;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $cantitateDeseuEliminata;

    /**
     *
     * @var type
     * @Assert\NotBlank()
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