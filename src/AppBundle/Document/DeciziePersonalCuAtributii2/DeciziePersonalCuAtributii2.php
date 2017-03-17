<?php

namespace AppBundle\Document\DeciziePersonalCuAtributii2;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Document\Common\Person;

class DeciziePersonalCuAtributii2
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
     * @Type("array<AppBundle\Document\Common\Person>")
     */
    protected $administrator;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $workersAttributions;

    public function __construct()
    {
        $x1 = new Person();
        $x1->setGender('');
        $x1->setName('');
        $x1->setFunction('formular.decizie-personal-cu-atributii-2.rsvti');
        $x2 = new Person();
        $x2->setGender('');
        $x2->setName('');
        $x2->setFunction('formular.decizie-personal-cu-atributii-2.compressor');
        $x3 = new Person();
        $x3->setGender('');
        $x3->setName('');
        $x3->setFunction('formular.decizie-personal-cu-atributii-2.container');
        $x4 = new Person();
        $x4->setGender('');
        $x4->setName('');
        $x4->setFunction('formular.decizie-personal-cu-atributii-2.auto');
        $x5 = new Person();
        $x5->setGender('');
        $x5->setName('');
        $x5->setFunction('formular.decizie-personal-cu-atributii-2.firstAid');

        $this->workersAttributions = new ArrayCollection();
        $workersAttributions = [$x1, $x2, $x3, $x4, $x5];
        $this->workersAttributions = $workersAttributions;

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

}