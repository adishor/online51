<?php

namespace AppBundle\Entity\DocumentForm\DecizieOrganizareActivitateSSM;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;

class DecizieOrganizareActivitateSSM
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
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $administrator;

    /**
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $designedWorkerForPreventionProtection;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $membersForPreventionProtectionService;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $leaders;

    public function __construct()
    {
        $this->membersForPreventionProtectionService = new ArrayCollection();
        $x = new Person();
        $x->setGender('');
        $x->setName('');
        $x->setFunction('formular.decizie-organizare-activitate-ssm.service-chief');
        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('');

        $membersForPreventionProtectionService = [$x, $y];
        $this->membersForPreventionProtectionService = $membersForPreventionProtectionService;

        $this->leaders = new ArrayCollection();
        $leaders = [$y];
        $this->leaders = $leaders;

        $this->administrator = new ArrayCollection();
        $this->designedWorkerForPreventionProtection = new ArrayCollection();

        $administrator = [$y];
        $designedWorkerForPreventionProtection = [$y];

        $this->administrator = $administrator;
        $this->designedWorkerForPreventionProtection = $designedWorkerForPreventionProtection;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function getDesignedWorkerForPreventionProtection()
    {
        return $this->designedWorkerForPreventionProtection;
    }

    public function getMembersForPreventionProtectionService()
    {
        return $this->membersForPreventionProtectionService;
    }

    public function getLeaders()
    {
        return $this->leaders;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function setDesignedWorkerForPreventionProtection($designedWorkerForPreventionProtection)
    {
        $this->designedWorkerForPreventionProtection = $designedWorkerForPreventionProtection;
    }

    public function setMembersForPreventionProtectionService($membersForPreventionProtectionService)
    {
        $this->membersForPreventionProtectionService = $membersForPreventionProtectionService;
    }

    public function setLeaders($leaders)
    {
        $this->leaders = $leaders;
    }

}