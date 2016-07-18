<?php

namespace AppBundle\Entity\DocumentForm\DecizieComisieCercetareAccidente;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;

class DecizieComisieCercetareAccidente
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
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $administrator;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\NotBlank()
     */
    protected $undersigned;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $accidentDate;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     * @Assert\Range(min=0, max=23)
     */
    protected $accidentHour;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $accidentDescription;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $companyAddress;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $members;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $startingActivity;

    /**
     * @var type
     * @Type("DateTime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $endingActivity;

    public function __construct()
    {
        $y = new Person();
        $y->setName('');
        $y->setFunction('');

        $this->members = new ArrayCollection();
        $members = [$y];
        $this->members = $members;

        $this->undersigned = new ArrayCollection();
        $undersigned = [$y];
        $this->undersigned = $undersigned;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function getUndersigned()
    {
        return $this->undersigned;
    }

    public function getAccidentDate()
    {
        return $this->accidentDate;
    }

    public function getAccidentHour()
    {
        return $this->accidentHour;
    }

    public function getAccidentDescription()
    {
        return $this->accidentDescription;
    }

    public function getCompanyAddress()
    {
        return $this->companyAddress;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getStartingActivity()
    {
        return $this->startingActivity;
    }

    public function getEndingActivity()
    {
        return $this->endingActivity;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function setUndersigned($undersigned)
    {
        $this->undersigned = $undersigned;
    }

    public function setAccidentDate($accidentDate)
    {
        $this->accidentDate = $accidentDate;
    }

    public function setAccidentHour($accidentHour)
    {
        $this->accidentHour = $accidentHour;
    }

    public function setAccidentDescription($accidentDescription)
    {
        $this->accidentDescription = $accidentDescription;
    }

    public function setCompanyAddress($companyAddress)
    {
        $this->companyAddress = $companyAddress;
    }

    public function setMembers($members)
    {
        $this->members = $members;
    }

    public function setStartingActivity($startingActivity)
    {
        $this->startingActivity = $startingActivity;
    }

    public function setEndingActivity($endingActivity)
    {
        $this->endingActivity = $endingActivity;
    }

}