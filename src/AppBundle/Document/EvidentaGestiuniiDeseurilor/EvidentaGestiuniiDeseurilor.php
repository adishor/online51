<?php

namespace AppBundle\Document\EvidentaGestiuniiDeseurilor;

use AppBundle\Document\GenericDocument;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;

class EvidentaGestiuniiDeseurilor
{

    /**
     * @var type
     * @Type("integer")
     */
    static public $startYear = 2015;

    /**
     * @var type
     * @Type("integer")
     */
    static public $startMonth = 3;

    /**
     * @var type
     * @Type("boolean")
     */
    static public $oneStepFormConfig = TRUE;

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
     * @var type
     * @Type("string")
     */
    protected $currentStep = 0;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $operatia;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $agentEconomic;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $an;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $tipDeseu;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $tipDeseuCod;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $stareFizica;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $unitateMasura;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $stocareTip;

    /**
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
    protected $lastYearInStock;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\EvidentaGestiuniiDeseurilor\EGDCompany>")
     * @Assert\Count(min="1")
     */
    protected $EGDCompany;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\EvidentaGestiuniiDeseurilor\EGD1GenerareDeseuri>")
     */
    protected $EGD1GenerareDeseuri;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\EvidentaGestiuniiDeseurilor\EGD2StocareTratareTransportDeseuri>")
     */
    protected $EGD2StocareTratareTransportDeseuri;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\EvidentaGestiuniiDeseurilor\EGD3ValorificareDeseuri>")
     */
    protected $EGD3ValorificareDeseuri;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\EvidentaGestiuniiDeseurilor\EGD4EliminareDeseuri>")
     */
    protected $EGD4EliminareDeseuri;

    public function __construct()
    {
        $this->lastYearInStock = 0;

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


    public function getUniqueFields()
    {
        return ['an', 'tip_deseu'];
    }

    public function getAgentEconomic()
    {
        return $this->agentEconomic;
    }

    public function getAn()
    {
        return $this->an;
    }

    public function getTipDeseu()
    {
        return $this->tipDeseu;
    }

    public function getTipDeseuCod()
    {
        return $this->tipDeseuCod;
    }

    public function getStareFizica()
    {
        return $this->stareFizica;
    }

    public function getUnitateMasura()
    {
        return $this->unitateMasura;
    }

    public function setAgentEconomic($agentEconomic)
    {
        $this->agentEconomic = $agentEconomic;
    }

    public function setAn($an)
    {
        $this->an = $an;
    }

    public function setTipDeseu($tipDeseu)
    {
        $this->tipDeseu = $tipDeseu;
    }

    public function setTipDeseuCod($tipDeseuCod)
    {
        $this->tipDeseuCod = $tipDeseuCod;
    }

    public function setStareFizica($stareFizica)
    {
        $this->stareFizica = $stareFizica;
    }

    public function setUnitateMasura($unitateMasura)
    {
        $this->unitateMasura = $unitateMasura;
    }

    public function getStocareTip()
    {
        return $this->stocareTip;
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

    public function setStocareTip($stocareTip)
    {
        $this->stocareTip = $stocareTip;
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

    public function getOperatiaDeValorificare()
    {
        return $this->operatiaDeValorificare;
    }

    public function getOperatiaDeEliminare()
    {
        return $this->operatiaDeEliminare;
    }

    public function setOperatiaDeValorificare($operatiaDeValorificare)
    {
        $this->operatiaDeValorificare = $operatiaDeValorificare;
    }

    public function setOperatiaDeEliminare($operatiaDeEliminare)
    {
        $this->operatiaDeEliminare = $operatiaDeEliminare;
    }

    public function getEGDCompany()
    {
        return $this->EGDCompany;
    }

    public function getEGD1GenerareDeseuri()
    {
        return $this->EGD1GenerareDeseuri;
    }

    public function getEGD2StocareTratareTransportDeseuri()
    {
        return $this->EGD2StocareTratareTransportDeseuri;
    }

    public function getEGD3ValorificareDeseuri()
    {
        return $this->EGD3ValorificareDeseuri;
    }

    public function getEGD4EliminareDeseuri()
    {
        return $this->EGD4EliminareDeseuri;
    }

    public function setEGDCompany($EGDCompany)
    {
        $this->EGDCompany = $EGDCompany;
    }

    public function getOperatia()
    {
        return $this->operatia;
    }

    public function setOperatia($operatia)
    {
        $this->operatia = $operatia;
    }

    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;
    }

    public function getLastYearInStock()
    {
        return $this->lastYearInStock;
    }

    public function setLastYearInStock($lastYearInStock)
    {
        $this->lastYearInStock = $lastYearInStock;
    }

}