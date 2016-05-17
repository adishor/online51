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
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $luna;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $sectia;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $stocareCantitate;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $stocareTip;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $tratareCantitate;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $tratareMod;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $tratareScop;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $transportMijloc;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument"})
     */
    protected $transportDestinatie;


    function getLuna()
    {
        return $this->luna;
    }

    function getSectia()
    {
        return $this->sectia;
    }

    function getStocareCantitate()
    {
        return $this->stocareCantitate;
    }

    function getStocareTip()
    {
        return $this->stocareTip;
    }

    function getTratareCantitate()
    {
        return $this->tratareCantitate;
    }

    function getTratareMod()
    {
        return $this->tratareMod;
    }

    function getTratareScop()
    {
        return $this->tratareScop;
    }

    function getTransportMijloc()
    {
        return $this->transportMijloc;
    }

    function getTransportDestinatie()
    {
        return $this->transportDestinatie;
    }

    function setLuna($luna)
    {
        $this->luna = $luna;
    }

    function setSectia($sectia)
    {
        $this->sectia = $sectia;
    }

    function setStocareCantitate($stocareCantitate)
    {
        $this->stocareCantitate = $stocareCantitate;
    }

    function setStocareTip($stocareTip)
    {
        $this->stocareTip = $stocareTip;
    }

    function setTratareCantitate($tratareCantitate)
    {
        $this->tratareCantitate = $tratareCantitate;
    }

    function setTratareMod($tratareMod)
    {
        $this->tratareMod = $tratareMod;
    }

    function setTratareScop($tratareScop)
    {
        $this->tratareScop = $tratareScop;
    }

    function setTransportMijloc($transportMijloc)
    {
        $this->transportMijloc = $transportMijloc;
    }

    function setTransportDestinatie($transportDestinatie)
    {
        $this->transportDestinatie = $transportDestinatie;
    }

}