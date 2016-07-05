<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;

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
    protected $presedinte;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $secretar;

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
        $this->membrii = new ArrayCollection();
        $y = new Person();
        $y->setName('');
        $y->setFunction('');

        $membrii = [$y, $y];
        $this->membrii = $membrii;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function getPresedinte()
    {
        return $this->presedinte;
    }

    public function getSecretar()
    {
        return $this->secretar;
    }

    public function getMedic()
    {
        return $this->medic;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function setPresedinte($presedinte)
    {
        $this->presedinte = $presedinte;
    }

    public function setSecretar($secretar)
    {
        $this->secretar = $secretar;
    }

    public function setMedic($medic)
    {
        $this->medic = $medic;
    }

    public function getMembrii()
    {
        return $this->membrii;
    }

}