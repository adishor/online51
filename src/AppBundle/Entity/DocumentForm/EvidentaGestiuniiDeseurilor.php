<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class EvidentaGestiuniiDeseurilor
{
    public $months = ['Ianuarie',
        'Februarie',
        'Martie',
        'Aprilie',
        'Mai',
        'Iunie',
        'Iulie',
        'August',
        'Septembrie',
        'Octombrie',
        'Noiembrie',
        'Decembrie',
        'TOTAL'
    ];

    /**
     *
     * @var type
     */
    protected $agentEconomic;

    /**
     *
     * @var type
     */
    protected $an;

    /**
     *
     * @var type
     */
    protected $tipDeseu;

    /**
     *
     * @var type
     */
    protected $tipDeseuCod;

    /**
     *
     * @var type
     */
    protected $stareFizica;

    /**
     *
     * @var type
     */
    protected $unitateMasura;

    /**
     *
     * @var type
     */
    protected $generareDeseuri;

    /**
     *
     * @var type
     */
    protected $stocareTratareTransportDeseuri;

    /**
     *
     * @var type
     */
    protected $valorificareDeseuri;

    /**
     *
     * @var type
     */
    protected $eliminareDeseuri;

    /**
     *
     * @var type
     */
    protected $EGD1GenerareDeseuri;

    /**
     *
     * @var type
     */
    protected $EGD2StocareTratareTransportDeseuri;

    /**
     *
     * @var type
     */
    protected $EGD3ValorificareDeseuri;

    /**
     *
     * @var type
     */
    protected $EGD4EliminareDeseuri;

    public function __construct()
    {
        $this->EGD1GenerareDeseuri = new ArrayCollection();
        $EGD1 = [];
        foreach ($this->months as $month) {
            $x = new EGD1GenerareDeseuri();
            $x->setLuna($month);
            $EGD1[] = $x;
        }
        $this->EGD1GenerareDeseuri = $EGD1;


        $this->EGD2StocareTratareTransportDeseuri = new ArrayCollection();
        $EGD2 = [];
        foreach ($this->months as $month) {
            $x = new EGD2StocareTratareTransportDeseuri();
            $x->setLuna($month);
            $EGD2[] = $x;
        }
        $this->EGD2StocareTratareTransportDeseuri = $EGD2;

        $this->EGD3ValorificareDeseuri = new ArrayCollection();
        $EGD3 = [];
        foreach ($this->months as $month) {
            $x = new EGD3ValorificareDeseuri();
            $x->setLuna($month);
            $EGD3[] = $x;
        }
        $this->EGD3ValorificareDeseuri = $EGD3;

        $this->EGD4EliminareDeseuri = new ArrayCollection();
        $EGD4 = [];
        foreach ($this->months as $month) {
            $x = new EGD4EliminareDeseuri();
            $x->setLuna($month);
            $EGD4[] = $x;
        }
        $this->EGD4EliminareDeseuri = $EGD4;
    }

    function getAgentEconomic()
    {
        return $this->agentEconomic;
    }

    function getAn()
    {
        return $this->an;
    }

    function getTipDeseu()
    {
        return $this->tipDeseu;
    }

    function getTipDeseuCod()
    {
        return $this->tipDeseuCod;
    }

    function getStareFizica()
    {
        return $this->stareFizica;
    }

    function getUnitateMasura()
    {
        return $this->unitateMasura;
    }

    function getGenerareDeseuri()
    {
        return $this->generareDeseuri;
    }

    function getStocareTratareTransportDeseuri()
    {
        return $this->stocareTratareTransportDeseuri;
    }

    function getValorificareDeseuri()
    {
        return $this->valorificareDeseuri;
    }

    function getEliminareDeseuri()
    {
        return $this->eliminareDeseuri;
    }

    function setAgentEconomic($agentEconomic)
    {
        $this->agentEconomic = $agentEconomic;
    }

    function setAn($an)
    {
        $this->an = $an;
    }

    function setTipDeseu($tipDeseu)
    {
        $this->tipDeseu = $tipDeseu;
    }

    function setTipDeseuCod($tipDeseuCod)
    {
        $this->tipDeseuCod = $tipDeseuCod;
    }

    function setStareFizica($stareFizica)
    {
        $this->stareFizica = $stareFizica;
    }

    function setUnitateMasura($unitateMasura)
    {
        $this->unitateMasura = $unitateMasura;
    }

    function setGenerareDeseuri($generareDeseuri)
    {
        $this->generareDeseuri = $generareDeseuri;
    }

    function setStocareTratareTransportDeseuri($stocareTratareTransportDeseuri)
    {
        $this->stocareTratareTransportDeseuri = $stocareTratareTransportDeseuri;
    }

    function setValorificareDeseuri($valorificareDeseuri)
    {
        $this->valorificareDeseuri = $valorificareDeseuri;
    }

    function setEliminareDeseuri($eliminareDeseuri)
    {
        $this->eliminareDeseuri = $eliminareDeseuri;
    }

    function getEGD1GenerareDeseuri()
    {
        return $this->EGD1GenerareDeseuri;
    }

    function getEGD2StocareTratareTransportDeseuri()
    {
        return $this->EGD2StocareTratareTransportDeseuri;
    }

    function getEGD3ValorificareDeseuri()
    {
        return $this->EGD3ValorificareDeseuri;
    }

    function getEGD4EliminareDeseuri()
    {
        return $this->EGD4EliminareDeseuri;
    }

}