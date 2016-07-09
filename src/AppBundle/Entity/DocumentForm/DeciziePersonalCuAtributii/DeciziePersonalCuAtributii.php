<?php

namespace AppBundle\Entity\DocumentForm\DeciziePersonalCuAtributii;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;
use AppBundle\Entity\DocumentForm\Common\Person;

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
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $angajatiConducatoriMunca;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $angajatiCondusAutoturisme;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $angajatiPrimAjutor;

    /**
     *
     * @var type
     * @Type("array<AppBundle\Entity\DocumentForm\Common\Person>")
     */
    protected $angajatiResponsabili;

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

    public function setCompany($company)
    {
        $this->company = $company;
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

    public function setAngajatiConducatoriMunca($angajatiConducatoriMunca)
    {
        $this->angajatiConducatoriMunca = $angajatiConducatoriMunca;
    }

    public function setAngajatiCondusAutoturisme($angajatiCondusAutoturisme)
    {
        $this->angajatiCondusAutoturisme = $angajatiCondusAutoturisme;
    }

    public function setAngajatiPrimAjutor($angajatiPrimAjutor)
    {
        $this->angajatiPrimAjutor = $angajatiPrimAjutor;
    }

    public function setAngajatiResponsabili($angajatiResponsabili)
    {
        $this->angajatiResponsabili = $angajatiResponsabili;
    }

}