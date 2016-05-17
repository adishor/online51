<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGD3ValorificareDeseuri
{
    /**
     *
     * @var type
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
    protected $cantitateDeseuValorificata;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $operatiaDeValorificare;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
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