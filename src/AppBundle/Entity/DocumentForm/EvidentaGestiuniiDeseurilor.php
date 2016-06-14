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
    static public $uniqueness = [self::UNIQUE_AN, 'tip_deseu'];

    /**
     * @var type
     * @Type("boolean")
     */
    static public $oneStepFormConfig = FALSE;

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
     * @Type("array<AppBundle\Entity\DocumentForm\EGDCompany>")
     * @Assert\Count(min="1")
     */
    protected $EGDCompany;

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

}