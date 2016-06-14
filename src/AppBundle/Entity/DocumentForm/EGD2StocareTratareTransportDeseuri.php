<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGD2StocareTratareTransportDeseuri
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
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $sectia;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank()
     * @Assert\Type(type="double")
     * @Assert\Range(min = 0)
     */
    protected $stocareCantitate;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $stocareTip;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank()
     * @Assert\Type(type="double")
     * @Assert\Range(min = 0)
     */
    protected $tratareCantitate;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $tratareMod;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $tratareScop;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $transportMijloc;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $transportDestinatie;


    public function getLuna()
    {
        return $this->luna;
    }

    public function getSectia()
    {
        return $this->sectia;
    }

    public function getStocareCantitate()
    {
        return $this->stocareCantitate;
    }

    public function getStocareTip()
    {
        return $this->stocareTip;
    }

    public function getTratareCantitate()
    {
        return $this->tratareCantitate;
    }

    public function getTratareMod()
    {
        return $this->tratareMod;
    }

    public function getTratareScop()
    {
        return $this->tratareScop;
    }

    public function getTransportMijloc()
    {
        return $this->transportMijloc;
    }

    public function getTransportDestinatie()
    {
        return $this->transportDestinatie;
    }

    public function setLuna($luna)
    {
        $this->luna = $luna;
    }

    public function setSectia($sectia)
    {
        $this->sectia = $sectia;
    }

    public function setStocareCantitate($stocareCantitate)
    {
        $this->stocareCantitate = $stocareCantitate;
    }

    public function setStocareTip($stocareTip)
    {
        $this->stocareTip = $stocareTip;
    }

    public function setTratareCantitate($tratareCantitate)
    {
        $this->tratareCantitate = $tratareCantitate;
    }

    public function setTratareMod($tratareMod)
    {
        $this->tratareMod = $tratareMod;
    }

    public function setTratareScop($tratareScop)
    {
        $this->tratareScop = $tratareScop;
    }

    public function setTransportMijloc($transportMijloc)
    {
        $this->transportMijloc = $transportMijloc;
    }

    public function setTransportDestinatie($transportDestinatie)
    {
        $this->transportDestinatie = $transportDestinatie;
    }

}