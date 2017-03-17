<?php

namespace AppBundle\Document\DeciziePersonalCuAtributii;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Document\Common\Person;

class DeciziePersonalCuAtributii
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
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $workersLeaders;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $workersCarsDriven;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $workersFirstAid;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Document\Common\Person>")
     * @Assert\Count(min="1")
     */
    protected $workersResponsible;

    public function __construct()
    {
        $x = new Person();
        $x->setGender('');
        $x->setName('');
        $x->setFunction('');

        $this->workersLeaders = new ArrayCollection();
        $workersLeaders = [$x];
        $this->workersLeaders = $workersLeaders;

        $this->workersCarsDriven = new ArrayCollection();
        $workersCarsDriven = [$x];
        $this->workersCarsDriven = $workersCarsDriven;

        $this->workersFirstAid = new ArrayCollection();
        $workersFirstAid = [$x];
        $this->workersFirstAid = $workersFirstAid;

        $this->workersResponsible = new ArrayCollection();
        $y = new Person();
        $y->setGender('');
        $y->setName('');
        $y->setFunction('formular.decizie-personal-cu-atributii.use-elevator');
        $z = new Person();
        $z->setGender('');
        $z->setName('');
        $z->setFunction('formular.decizie-personal-cu-atributii.maintenance');
        $workersResponsible = [$y, $z];
        $this->workersResponsible = $workersResponsible;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getWorkersLeaders()
    {
        return $this->workersLeaders;
    }

    public function getWorkersCarsDriven()
    {
        return $this->workersCarsDriven;
    }

    public function getWorkersFirstAid()
    {
        return $this->workersFirstAid;
    }

    public function getWorkersResponsible()
    {
        return $this->workersResponsible;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setWorkersLeaders($workersLeaders)
    {
        $this->workersLeaders = $workersLeaders;
    }

    public function setWorkersCarsDriven($workersCarsDriven)
    {
        $this->workersCarsDriven = $workersCarsDriven;
    }

    public function setWorkersFirstAid($workersFirstAid)
    {
        $this->workersFirstAid = $workersFirstAid;
    }

    public function setWorkersResponsible($workersResponsible)
    {
        $this->workersResponsible = $workersResponsible;
    }

}