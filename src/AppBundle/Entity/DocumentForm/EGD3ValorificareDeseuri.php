<?php

namespace AppBundle\Entity\DocumentForm;

class EGD3ValorificareDeseuri
{
    protected $luna;

    protected $cantitateDeseuValorificata;

    protected $operatiaDeValorificare;

    protected $agentEconomicValorificare;


    function getLuna()
    {
        return $this->luna;
    }

    function getCantitateDeseuValorificata()
    {
        return $this->cantitateDeseuValorificata;
    }

    function getOperatiaDeValorificare()
    {
        return $this->operatiaDeValorificare;
    }

    function getAgentEconomicValorificare()
    {
        return $this->agentEconomicValorificare;
    }

    function setLuna($luna)
    {
        $this->luna = $luna;
    }

    function setCantitateDeseuValorificata($cantitateDeseuValorificata)
    {
        $this->cantitateDeseuValorificata = $cantitateDeseuValorificata;
    }

    function setOperatiaDeValorificare($operatiaDeValorificare)
    {
        $this->operatiaDeValorificare = $operatiaDeValorificare;
    }

    function setAgentEconomicValorificare($agentEconomicValorificare)
    {
        $this->agentEconomicValorificare = $agentEconomicValorificare;
    }

}