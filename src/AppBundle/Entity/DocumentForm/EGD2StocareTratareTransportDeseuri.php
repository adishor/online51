<?php

namespace AppBundle\Entity\DocumentForm;

class EGD2StocareTratareTransportDeseuri
{
    protected $luna;

    protected $sectia;

    protected $stocareCantitate;

    protected $stocareTip;

    protected $tratareCantitate;

    protected $tratareMod;

    protected $tratareScop;

    protected $transportMijloc;

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