<?php

namespace AppBundle\Entity\DocumentForm\DecizieComponentaCSSM;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;

class DecizieComponentaCSSM
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
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $president;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $secretary;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $members;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $doctor;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $y = new Person();
        $y->setName('');
        $y->setFunction('');

        $members = [$y];
        $this->members = $members;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
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

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
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

}