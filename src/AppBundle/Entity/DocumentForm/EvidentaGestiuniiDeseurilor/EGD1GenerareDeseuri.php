<?php

namespace AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGD1GenerareDeseuri
{
    /**
     *
     * @var type
     *
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
    protected $cantitateDeseuGenerate;

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
     * @Type("double")
     * @Assert\NotBlank()
     * @Assert\Type(type="double")
     * @Assert\Range(min = 0)
     */
    protected $cantitateDeseuEliminata;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank()
     * @Assert\Type(type="double")
     * @Assert\Range(min = 0)
     */
    protected $cantitateDeseuInStoc;

    public function getLuna()
    {
        return $this->luna;
    }

    public function getCantitateDeseuGenerate()
    {
        return $this->cantitateDeseuGenerate;
    }

    public function getCantitateDeseuValorificata()
    {
        return $this->cantitateDeseuValorificata;
    }

    public function getCantitateDeseuEliminata()
    {
        return $this->cantitateDeseuEliminata;
    }

    public function getCantitateDeseuInStoc()
    {
        return $this->cantitateDeseuInStoc;
    }

    public function setLuna($luna)
    {
        $this->luna = $luna;
    }

    public function setCantitateDeseuGenerate($cantitateDeseuGenerate)
    {
        $this->cantitateDeseuGenerate = $cantitateDeseuGenerate;
    }

    public function setCantitateDeseuValorificata($cantitateDeseuValorificata)
    {
        $this->cantitateDeseuValorificata = $cantitateDeseuValorificata;
    }

    public function setCantitateDeseuEliminata($cantitateDeseuEliminata)
    {
        $this->cantitateDeseuEliminata = $cantitateDeseuEliminata;
    }

    public function setCantitateDeseuInStoc($cantitateDeseuInStoc)
    {
        $this->cantitateDeseuInStoc = $cantitateDeseuInStoc;
    }

}