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
     * @Assert\NotBlank()
     */
    protected $luna;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank()
     * @Assert\Type(type="double")
     * @Assert\Range(min = 0)
     */
    protected $cantitateDeseuEliminata;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $operatiaDeEliminare;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $agentEconomicEliminare;

    public function getLuna()
    {
        return $this->luna;
    }

    public function getCantitateDeseuEliminata()
    {
        return $this->cantitateDeseuEliminata;
    }

    public function getOperatiaDeEliminare()
    {
        return $this->operatiaDeEliminare;
    }

    public function getAgentEconomicEliminare()
    {
        return $this->agentEconomicEliminare;
    }

    public function setLuna($luna)
    {
        $this->luna = $luna;
    }

    public function setCantitateDeseuEliminata($cantitateDeseuEliminata)
    {
        $this->cantitateDeseuEliminata = $cantitateDeseuEliminata;
    }

    public function setOperatiaDeEliminare($operatiaDeEliminare)
    {
        $this->operatiaDeEliminare = $operatiaDeEliminare;
    }

    public function setAgentEconomicEliminare($agentEconomicEliminare)
    {
        $this->agentEconomicEliminare = $agentEconomicEliminare;
    }

}