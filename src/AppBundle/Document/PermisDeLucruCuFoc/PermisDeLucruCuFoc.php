<?php

namespace AppBundle\Entity\DocumentForm\PermisDeLucruCuFoc;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;

class PermisDeLucruCuFoc
{
    /**
     * @var array
     * @Type("array")
     */
    static public $uniqueness = null;

    /**
     * @var type
     * @Type("boolean")
     */
    static public $oneStepFormConfig = TRUE;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $company;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     */
    protected $personWithWorkPermitForFire;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     */
    protected $helpPersonForWorkWithFire;

    /**
     * @var type
     * @Type("string")
     */
    protected $executeWork;

    /**
     * @var type
     * @Type("string")
     */
    protected $useForWork;

    /**
     * @var type
     * @Type("string")
     */
    protected $forWork;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $startWorkDate;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $endWorkDate;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure1ProtectionRadiusOfMeters;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure1;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure2;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure3;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure5No;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\DateTime()
     */
    protected $measure5Date;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure5ReleasedBy;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure6;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure7;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     */
    protected $measure9SurveillanceBy;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     */
    protected $measure11AssuredBy;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     */
    protected $measure12SurveillanceBy;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure13AnnoucementAt;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure13AnnoucementTrought;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure14;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure15Issuer;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure15ChiefSector;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure15Workers;

    /**
     * @var type
     * @Type("string")
     */
    protected $measure15Emergency;

    public function __construct()
    {
        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('');

        $this->personWithWorkPermitForFire = new ArrayCollection();
        $personWithWorkPermitForFire = [$y];
        $this->personWithWorkPermitForFire = $personWithWorkPermitForFire;

        $this->helpPersonForWorkWithFire = new ArrayCollection();
        $helpPersonForWorkWithFire = [$y];
        $this->helpPersonForWorkWithFire = $helpPersonForWorkWithFire;

        $this->measure9SurveillanceBy = new ArrayCollection();
        $measure9SurveillanceBy = [$y];
        $this->measure9SurveillanceBy = $measure9SurveillanceBy;

        $this->measure11AssuredBy = new ArrayCollection();
        $measure11AssuredBy = [$y];
        $this->measure11AssuredBy = $measure11AssuredBy;

        $this->measure12SurveillanceBy = new ArrayCollection();
        $measure12SurveillanceBy = [$y];
        $this->measure12SurveillanceBy = $measure12SurveillanceBy;
    }

    public function getPersonWithWorkPermitForFire()
    {
        return $this->personWithWorkPermitForFire;
    }

    public function getHelpPersonForWorkWithFire()
    {
        return $this->helpPersonForWorkWithFire;
    }

    public function getExecuteWork()
    {
        return $this->executeWork;
    }

    public function getUseForWork()
    {
        return $this->useForWork;
    }

    public function getForWork()
    {
        return $this->forWork;
    }

    public function getStartWorkDate()
    {
        return $this->startWorkDate;
    }

    public function getEndWorkDate()
    {
        return $this->endWorkDate;
    }

    public function getMeasure1ProtectionRadiusOfMeters()
    {
        return $this->measure1ProtectionRadiusOfMeters;
    }

    public function getMeasure1()
    {
        return $this->measure1;
    }

    public function getMeasure2()
    {
        return $this->measure2;
    }

    public function getMeasure3()
    {
        return $this->measure3;
    }

    public function getMeasure5No()
    {
        return $this->measure5No;
    }

    public function getMeasure5Date()
    {
        return $this->measure5Date;
    }

    public function getMeasure5ReleasedBy()
    {
        return $this->measure5ReleasedBy;
    }

    public function getMeasure6()
    {
        return $this->measure6;
    }

    public function getMeasure7()
    {
        return $this->measure7;
    }

    public function getMeasure9SurveillanceBy()
    {
        return $this->measure9SurveillanceBy;
    }

    public function getMeasure11AssuredBy()
    {
        return $this->measure11AssuredBy;
    }

    public function getMeasure12SurveillanceBy()
    {
        return $this->measure12SurveillanceBy;
    }

    public function getMeasure13AnnoucementAt()
    {
        return $this->measure13AnnoucementAt;
    }

    public function getMeasure13AnnoucementTrought()
    {
        return $this->measure13AnnoucementTrought;
    }

    public function getMeasure14()
    {
        return $this->measure14;
    }

    public function getMeasure15Issuer()
    {
        return $this->measure15Issuer;
    }

    public function getMeasure15ChiefSector()
    {
        return $this->measure15ChiefSector;
    }

    public function getMeasure15Workers()
    {
        return $this->measure15Workers;
    }

    public function getMeasure15Emergency()
    {
        return $this->measure15Emergency;
    }

    public function setPersonWithWorkPermitForFire($personWithWorkPermitForFire)
    {
        $this->personWithWorkPermitForFire = $personWithWorkPermitForFire;
    }

    public function setHelpPersonForWorkWithFire($helpPersonForWorkWithFire)
    {
        $this->helpPersonForWorkWithFire = $helpPersonForWorkWithFire;
    }

    public function setExecuteWork($executeWork)
    {
        $this->executeWork = $executeWork;
    }

    public function setUseForWork($useForWork)
    {
        $this->useForWork = $useForWork;
    }

    public function setForWork($forWork)
    {
        $this->forWork = $forWork;
    }

    public function setStartWorkDate($startWorkDate)
    {
        $this->startWorkDate = $startWorkDate;
    }

    public function setEndWorkDate($endWorkDate)
    {
        $this->endWorkDate = $endWorkDate;
    }

    public function setMeasure1ProtectionRadiusOfMeters($measure1ProtectionRadiusOfMeters)
    {
        $this->measure1ProtectionRadiusOfMeters = $measure1ProtectionRadiusOfMeters;
    }

    public function setMeasure1($measure1)
    {
        $this->measure1 = $measure1;
    }

    public function setMeasure2($measure2)
    {
        $this->measure2 = $measure2;
    }

    public function setMeasure3($measure3)
    {
        $this->measure3 = $measure3;
    }

    public function setMeasure5No($measure5No)
    {
        $this->measure5No = $measure5No;
    }

    public function setMeasure5Date($measure5Date)
    {
        $this->measure5Date = $measure5Date;
    }

    public function setMeasure5ReleasedBy($measure5ReleasedBy)
    {
        $this->measure5ReleasedBy = $measure5ReleasedBy;
    }

    public function setMeasure6($measure6)
    {
        $this->measure6 = $measure6;
    }

    public function setMeasure7($measure7)
    {
        $this->measure7 = $measure7;
    }

    public function setMeasure9SurveillanceBy($measure9SurveillanceBy)
    {
        $this->measure9SurveillanceBy = $measure9SurveillanceBy;
    }

    public function setMeasure11AssuredBy($measure11AssuredBy)
    {
        $this->measure11AssuredBy = $measure11AssuredBy;
    }

    public function setMeasure12SurveillanceBy($measure12SurveillanceBy)
    {
        $this->measure12SurveillanceBy = $measure12SurveillanceBy;
    }

    public function setMeasure13AnnoucementAt($measure13AnnoucementAt)
    {
        $this->measure13AnnoucementAt = $measure13AnnoucementAt;
    }

    public function setMeasure13AnnoucementTrought($measure13AnnoucementTrought)
    {
        $this->measure13AnnoucementTrought = $measure13AnnoucementTrought;
    }

    public function setMeasure14($measure14)
    {
        $this->measure14 = $measure14;
    }

    public function setMeasure15Issuer($measure15Issuer)
    {
        $this->measure15Issuer = $measure15Issuer;
    }

    public function setMeasure15ChiefSector($measure15ChiefSector)
    {
        $this->measure15ChiefSector = $measure15ChiefSector;
    }

    public function setMeasure15Workers($measure15Workers)
    {
        $this->measure15Workers = $measure15Workers;
    }

    public function setMeasure15Emergency($measure15Emergency)
    {
        $this->measure15Emergency = $measure15Emergency;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

}