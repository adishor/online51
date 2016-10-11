<?php

namespace AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGD3ValorificareDeseuri
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
    protected $cantitateDeseuValorificata;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $operatiaDeValorificare;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor\EGDCompany>")
     * @Assert\Count(min="1")
     */
    protected $agentEconomicValorificare;

    public function getLuna()
    {
        return $this->luna;
    }

    public function getCantitateDeseuValorificata()
    {
        return $this->cantitateDeseuValorificata;
    }

    public function getOperatiaDeValorificare()
    {
        return $this->operatiaDeValorificare;
    }

    public function getAgentEconomicValorificare()
    {
        return $this->agentEconomicValorificare;
    }

    public function setLuna($luna)
    {
        $this->luna = $luna;
    }

    public function setCantitateDeseuValorificata($cantitateDeseuValorificata)
    {
        $this->cantitateDeseuValorificata = $cantitateDeseuValorificata;
    }

    public function setOperatiaDeValorificare($operatiaDeValorificare)
    {
        $this->operatiaDeValorificare = $operatiaDeValorificare;
    }

    public function setAgentEconomicValorificare($agentEconomicValorificare)
    {
        $this->agentEconomicValorificare = $agentEconomicValorificare;
    }

}