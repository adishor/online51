<?php

namespace AppBundle\Entity\DocumentForm\ConvocatorCSSM;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;
use AppBundle\Entity\DocumentForm\Common\ConvocatorCSSMPunct;

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
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $administrator;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $secretary;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\ConvocatorCSSMPunct>")
     * @Assert\Count(min="1")
     */
    protected $meetingPoints;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $members;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $doctor;

    public function __construct()
    {
        $this->meetingPoints = new ArrayCollection();
        $x = new ConvocatorCSSMPunct();
        $x->setMeetingPoint('');
        $x->setMeetingPointSummary('');

        $meetingPoints = [$x];
        $this->meetingPoints = $meetingPoints;


        $this->members = new ArrayCollection();
        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('');

        $members = [$y];
        $this->members = $members;

        $this->administrator = new ArrayCollection();
        $this->secretary = new ArrayCollection();
        $this->doctor = new ArrayCollection();

        $administrator = [$y];
        $secretary = [$y];
        $doctor = [$y];

        $this->administrator = $administrator;
        $this->secretary = $secretary;
        $this->doctor = $doctor;
    }

    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    public function getMeetingHour()
    {
        return $this->meetingHour;
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

    public function getSecretary()
    {
        return $this->secretary;
    }

    public function getMeetingPoints()
    {
        return $this->meetingPoints;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getDoctor()
    {
        return $this->doctor;
    }

    public function setMeetingDate($meetingDate)
    {
        $this->meetingDate = $meetingDate;
    }

    public function setMeetingHour($meetingHour)
    {
        $this->meetingHour = $meetingHour;
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

    public function setSecretary($secretary)
    {
        $this->secretary = $secretary;
    }

    public function setMeetingPoints($meetingPoints)
    {
        $this->meetingPoints = $meetingPoints;
    }

    public function setMembers($members)
    {
        $this->members = $members;
    }

    public function setDoctor($doctor)
    {
        $this->doctor = $doctor;
    }

}