<?php

namespace AppBundle\Document\ProcesVerbalSedintaCSSM;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Document\Common\Person;
use AppBundle\Document\Common\ConvocatorCSSMPunct;

class ProcesVerbalSedintaCSSM
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
    protected $room;

    /**
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     */
    protected $president;

    /**
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     */
    protected $secretary;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $members;

    /**
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     */
    protected $doctor;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\ConvocatorCSSMPunct>")
     * @Assert\Count(min="1")
     */
    protected $meetingPoints;

    public function __construct()
    {
        $x = new ConvocatorCSSMPunct();
        $x->setMeetingPoint('');
        $x->setMeetingPointSummary('');

        $this->meetingPoints = new ArrayCollection();
        $meetingPoints = [$x];
        $this->meetingPoints = $meetingPoints;

        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('');

        $this->members = new ArrayCollection();
        $members = [$y];
        $this->members = $members;

        $this->president = new ArrayCollection();
        $president = [$y];
        $this->president = $president;

        $this->secretary = new ArrayCollection();
        $secretary = [$y];
        $this->secretary = $secretary;

        $this->doctor = new ArrayCollection();
        $doctor = [$y];
        $this->doctor = $doctor;
    }

    public function getMeetingDate()
    {
        return $this->meetingDate;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getCompanyCity()
    {
        return $this->companyCity;
    }

    public function getRoom()
    {
        return $this->room;
    }

    public function getPresident()
    {
        return $this->president;
    }

    public function getSecretary()
    {
        return $this->secretary;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getDoctor()
    {
        return $this->doctor;
    }

    public function getMeetingPoints()
    {
        return $this->meetingPoints;
    }

    public function setMeetingDate($meetingDate)
    {
        $this->meetingDate = $meetingDate;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setCompanyCity($companyCity)
    {
        $this->companyCity = $companyCity;
    }

    public function setRoom($room)
    {
        $this->room = $room;
    }

    public function setPresident($president)
    {
        $this->president = $president;
    }

    public function setSecretary($secretary)
    {
        $this->secretary = $secretary;
    }

    public function setMembers($members)
    {
        $this->members = $members;
    }

    public function setDoctor($doctor)
    {
        $this->doctor = $doctor;
    }

    public function setMeetingPoints($meetingPoints)
    {
        $this->meetingPoints = $meetingPoints;
    }

}