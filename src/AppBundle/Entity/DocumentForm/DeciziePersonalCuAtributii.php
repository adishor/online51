<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Person;

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
     * @Type("array<AppBundle\Entity\DocumentForm\Person>")
     */
    protected $angajatiConducatoriMunca;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Person>")
     */
    protected $angajatiCondusAutoturisme;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Person>")
     */
    protected $angajatiPrimAjutor;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Person>")
     */
    protected $angajatiResponsabili;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $administrator;

    public function __construct()
    {
        $x = new Person();
        $x->setName('');
        $x->setFunction('');

        $this->angajatiConducatoriMunca = new ArrayCollection();
        $angajatiConducatoriMunca = [$x, $x, $x];
        $this->angajatiConducatoriMunca = $angajatiConducatoriMunca;

        $this->angajatiCondusAutoturisme = new ArrayCollection();
        $angajatiCondusAutoturisme = [$x, $x, $x];
        $this->angajatiCondusAutoturisme = $angajatiCondusAutoturisme;

        $this->angajatiPrimAjutor = new ArrayCollection();
        $angajatiPrimAjutor = [$x];
        $this->angajatiPrimAjutor = $angajatiPrimAjutor;

        $this->angajatiResponsabili = new ArrayCollection();

        $y = new Person();
        $y->setName('');
        $y->setFunction('utilizarea liftului pentru marfuri');

        $z = new Person();
        $z->setName('');
        $z->setFunction('mentenanta echipamentelor, deservirea compresorului');
        $angajatiResponsabili = [$y, $z];
        $this->angajatiResponsabili = $angajatiResponsabili;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    public function getAngajatiConducatoriMunca()
    {
        return $this->angajatiConducatoriMunca;
    }

    public function getAngajatiCondusAutoturisme()
    {
        return $this->angajatiCondusAutoturisme;
    }

    public function getAngajatiPrimAjutor()
    {
        return $this->angajatiPrimAjutor;
    }

    public function getAngajatiResponsabili()
    {
        return $this->angajatiResponsabili;
    }

}