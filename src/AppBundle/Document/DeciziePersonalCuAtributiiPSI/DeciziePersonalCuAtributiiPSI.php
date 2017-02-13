<?php

namespace AppBundle\Document\DeciziePersonalCuAtributiiPSI;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;

class DeciziePersonalCuAtributiiPSI
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
    protected $administrator;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $workersAttributions;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $interventionTeam;

    public function __construct()
    {
        $x1 = new Person();
        $x1->setGender('');
        $x1->setName('');
        $x1->setFunction('formular.decizie-personal-cu-atributii-psi.educate');
        $x2 = new Person();
        $x2->setGender('');
        $x2->setName('');
        $x2->setFunction('formular.decizie-personal-cu-atributii-psi.compressor');
        $x3 = new Person();
        $x3->setGender('');
        $x3->setName('');
        $x3->setFunction('formular.decizie-personal-cu-atributii-psi.heating');
        $x4 = new Person();
        $x4->setGender('');
        $x4->setName('');
        $x4->setFunction('formular.decizie-personal-cu-atributii-psi.waste');

        $this->workersAttributions = new ArrayCollection();
        $workersAttributions = [$x1, $x2, $x3, $x4];
        $this->workersAttributions = $workersAttributions;

        $y1 = new Person();
        $y1->setGender('');
        $y1->setName('');
        $y1->setFunction('formular.decizie-personal-cu-atributii-psi.leads-intervention');
        $y2 = new Person();
        $y2->setGender('');
        $y2->setName('');
        $y2->setFunction('formular.decizie-personal-cu-atributii-psi.notify-the-administrator-society');
        $y3 = new Person();
        $y3->setGender('');
        $y3->setName('');
        $y3->setFunction('formular.decizie-personal-cu-atributii-psi.notify-emergency-dispatch');
        $y4 = new Person();
        $y4->setGender('');
        $y4->setName('');
        $y4->setFunction('formular.decizie-personal-cu-atributii-psi.pause-energy-and-gas');
        $y5 = new Person();
        $y5->setGender('');
        $y5->setName('');
        $y5->setFunction('formular.decizie-personal-cu-atributii-psi.act-with-extinguishers');
        $y6 = new Person();
        $y6->setGender('');
        $y6->setName('');
        $y6->setFunction('formular.decizie-personal-cu-atributii-psi.act-with-hydrants');
        $y7 = new Person();
        $y7->setGender('');
        $y7->setName('');
        $y7->setFunction('formular.decizie-personal-cu-atributii-psi.ensures-evacuation');
        $y8 = new Person();
        $y8->setGender('');
        $y8->setName('');
        $y8->setFunction('formular.decizie-personal-cu-atributii-psi.ensures-evacuation-of-property');

        $this->interventionTeam = new ArrayCollection();
        $interventionTeam = [$y1, $y2, $y3, $y4, $y5, $y6, $y7, $y8];
        $this->interventionTeam = $interventionTeam;

        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('');

        $this->administrator = new ArrayCollection();
        $administrator = [$y];
        $this->administrator = $administrator;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function getWorkersAttributions()
    {
        return $this->workersAttributions;
    }

    public function getInterventionTeam()
    {
        return $this->interventionTeam;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function setWorkersAttributions($workersAttributions)
    {
        $this->workersAttributions = $workersAttributions;
    }

    public function setInterventionTeam($interventionTeam)
    {
        $this->interventionTeam = $interventionTeam;
    }

}