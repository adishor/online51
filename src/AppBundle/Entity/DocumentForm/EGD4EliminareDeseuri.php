<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGD4EliminareDeseuri
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
    protected $cantitateDeseuEliminata;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $operatiaDeEliminare;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
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