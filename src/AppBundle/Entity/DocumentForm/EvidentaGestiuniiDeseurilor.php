<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class EvidentaGestiuniiDeseurilor
{
    const UNIQUE_AN = 'an';

    // Pentru valorile declarate se vor declara optiunile disponibile din select in fisierul yml generat corespunzator entitatii
    // exemplu pentru entitatea EvidentaGestiuniiDeseurilor avem fisierul yml evidenta_gestiunii_deseurilor.yml
    // locatia fisierului yml este config/documentForm/evidenta_gestiunii_deseurilor.yml
    static public $uniqueness = [self::UNIQUE_AN, 'tip_deseu', 'operatia'];

    private $luni = ['Ianuarie',
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
     * @Assert\NotBlank()
     */
    protected $agentEconomic;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $an;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $tipDeseu;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $tipDeseuCod;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $stareFizica;

    /**
     *
     * @var type
     * @Assert\NotBlank()
     */
    protected $unitateMasura;

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
        foreach ($this->luni as $luna) {
            $x = new EGD1GenerareDeseuri();
            $x->setLuna($luna);
            $EGD1[] = $x;
        }
        $this->EGD1GenerareDeseuri = $EGD1;


        $this->EGD2StocareTratareTransportDeseuri = new ArrayCollection();
        $EGD2 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD2StocareTratareTransportDeseuri();
            $x->setLuna($luna);
            $EGD2[] = $x;
        }
        $this->EGD2StocareTratareTransportDeseuri = $EGD2;

        $this->EGD3ValorificareDeseuri = new ArrayCollection();
        $EGD3 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD3ValorificareDeseuri();
            $x->setLuna($luna);
            $EGD3[] = $x;
        }
        $this->EGD3ValorificareDeseuri = $EGD3;

        $this->EGD4EliminareDeseuri = new ArrayCollection();
        $EGD4 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD4EliminareDeseuri();
            $x->setLuna($luna);
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