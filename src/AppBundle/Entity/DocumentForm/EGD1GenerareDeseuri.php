<?php

namespace AppBundle\Entity\DocumentForm;

class EGD1GenerareDeseuri
{
    protected $luna;

    protected $cantitateDeseuGenerate;

    protected $cantitateDeseuValorificata;

    protected $cantitateDeseuEliminata;

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