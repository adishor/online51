<?php

namespace AppBundle\Entity\DocumentForm;

class EGD4EliminareDeseuri
{
    protected $luna;

    protected $cantitateDeseuEliminata;

    protected $operatiaDeEliminare;

    protected $agentEconomicEliminare;


    function getLuna()
    {
        return $this->luna;
    }

    function getCantitateDeseuEliminata()
    {
        return $this->cantitateDeseuEliminata;
    }

    function getOperatiaDeEliminare()
    {
        return $this->operatiaDeEliminare;
    }

    function getAgentEconomicEliminare()
    {
        return $this->agentEconomicEliminare;
    }

    function setLuna($luna)
    {
        $this->luna = $luna;
    }

    function setCantitateDeseuEliminata($cantitateDeseuEliminata)
    {
        $this->cantitateDeseuEliminata = $cantitateDeseuEliminata;
    }

    function setOperatiaDeEliminare($operatiaDeEliminare)
    {
        $this->operatiaDeEliminare = $operatiaDeEliminare;
    }

    function setAgentEconomicEliminare($agentEconomicEliminare)
    {
        $this->agentEconomicEliminare = $agentEconomicEliminare;
    }

}