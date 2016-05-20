<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;

class EvidentaGestiuniiDeseurilor
{
    const UNIQUE_AN = 'an';

    // Pentru valorile declarate se vor declara optiunile disponibile din select in fisierul yml generat corespunzator entitatii
    // exemplu pentru entitatea EvidentaGestiuniiDeseurilor avem fisierul yml evidenta_gestiunii_deseurilor.yml
    // locatia fisierului yml este config/documentForm/evidenta_gestiunii_deseurilor.yml
    /**
     * @var array
     * @Type("array")
     */
    static public $uniqueness = [self::UNIQUE_AN, 'tip_deseu', 'operatia'];

    /**
     * @var array
     * @Type("array")
     */
    public $luni = ['Ianuarie',
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
    ];

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $agentEconomic;

    /**
     *
     * @var type
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $an;

    /**
     *
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $tipDeseu;

    /**
     *
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $tipDeseuCod;

    /**
     *
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $stareFizica;

    /**
     *
     * @Type("string")
     * @Assert\NotBlank(groups={"generateDocument", "button1"})
     */
    protected $unitateMasura;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\EGD1GenerareDeseuri>")
     */
    protected $EGD1GenerareDeseuri;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\EGD2StocareTratareTransportDeseuri>")
     */
    protected $EGD2StocareTratareTransportDeseuri;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\EGD3ValorificareDeseuri>")
     */
    protected $EGD3ValorificareDeseuri;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\EGD4EliminareDeseuri>")
     */
    protected $EGD4EliminareDeseuri;

    public function __construct()
    {
        $this->EGD1GenerareDeseuri = new ArrayCollection();
        $EGD1 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD1GenerareDeseuri();
            $x->setLuna($luna);
            $x->setCantitateDeseuGenerate(0);
            $x->setCantitateDeseuValorificata(0);
            $x->setCantitateDeseuEliminata(0);
            $x->setCantitateDeseuInStoc(0);
            $EGD1[] = $x;
        }
        $this->EGD1GenerareDeseuri = $EGD1;


        $this->EGD2StocareTratareTransportDeseuri = new ArrayCollection();
        $EGD2 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD2StocareTratareTransportDeseuri();
            $x->setLuna($luna);
            $x->setStocareCantitate(0);
            $x->setTratareCantitate(0);
            $EGD2[] = $x;
        }
        $this->EGD2StocareTratareTransportDeseuri = $EGD2;

        $this->EGD3ValorificareDeseuri = new ArrayCollection();
        $EGD3 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD3ValorificareDeseuri();
            $x->setLuna($luna);
            $x->setCantitateDeseuValorificata(0);
            $EGD3[] = $x;
        }
        $this->EGD3ValorificareDeseuri = $EGD3;

        $this->EGD4EliminareDeseuri = new ArrayCollection();
        $EGD4 = [];
        foreach ($this->luni as $luna) {
            $x = new EGD4EliminareDeseuri();
            $x->setLuna($luna);
            $x->setCantitateDeseuEliminata(0);
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