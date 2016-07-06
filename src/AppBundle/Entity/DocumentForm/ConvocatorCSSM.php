<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Person;
use AppBundle\Entity\DocumentForm\ConvocatorCSSMPunct;

class ConvocatorCSSM
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
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $meetingDate;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     * @Assert\Range(min=0, max=23)
     */
    protected $meetingHour;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $company;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $companyCity;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $companyCounty;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $administrator;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $secretar;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\ConvocatorCSSMPunct>")
     */
    protected $puncteOrdineIntrunire;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Person>")
     */
    protected $membrii;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $medic;

    public function __construct()
    {
        $this->puncteOrdineIntrunire = new ArrayCollection();
        $x = new ConvocatorCSSMPunct();
        $x->setPunctOrdineZi('');

        $puncteOrdineIntrunire = [$x, $x, $x];
        $this->puncteOrdineIntrunire = $puncteOrdineIntrunire;


        $this->membrii = new ArrayCollection();
        $y = new Person();
        $y->setName('');
        $y->setFunction('');

        $membrii = [$y, $y, $y];
        $this->membrii = $membrii;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getCompanyCity()
    {
        return $this->companyCity;
    }

    public function getCompanyCounty()
    {
        return $this->companyCounty;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function getSecretar()
    {
        return $this->secretar;
    }

    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    public function getMeetingHour()
    {
        return $this->meetingHour;
    }

    public function getMedic()
    {
        return $this->medic;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setCompanyCity($companyCity)
    {
        $this->companyCity = $companyCity;
    }

    public function setCompanyCounty($companyCounty)
    {
        $this->companyCounty = $companyCounty;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function setSecretar($secretar)
    {
        $this->secretar = $secretar;
    }

    public function setMeetingDate($meetingDate)
    {
        $this->meetingDate = $meetingDate;
    }

    public function setMeetingHour($meetingHour)
    {
        $this->meetingHour = $meetingHour;
    }

    public function setMedic($medic)
    {
        $this->medic = $medic;
    }

    public function getPuncteOrdineIntrunire()
    {
        return $this->puncteOrdineIntrunire;
    }

    public function getMembrii()
    {
        return $this->membrii;
    }

    public function setPuncteOrdineIntrunire($puncteOrdineIntrunire)
    {
        $this->puncteOrdineIntrunire = $puncteOrdineIntrunire;
    }

    public function setMembrii($membrii)
    {
        $this->membrii = $membrii;
    }

}