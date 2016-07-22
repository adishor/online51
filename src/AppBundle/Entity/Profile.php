<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="user_profile")
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Profile
{
    const FUNCTION_EXTERN_JOB = 1;
    const FUNCTION_INTERN_JOB = 2;
    const FUNCTION_APPOINTED_WORKER = 3;
    const FUNCTION_ADMINISTRATOR = 4;
    const NO_EMPLOYEES_0_9 = 2;
    const NO_EMPLOYEES_10_49 = 3;
    const NO_EMPLOYEES_OVER_50 = 4;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="user.required.name", groups={"flow_register_flow_step1", "AdminRegistration", "AdminProfile", "ChangeInfo"})
     * @ORM\Column()
     */
    protected $name;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.company", groups={"flow_register_flow_step1", "AdminRegistration", "AdminProfile", "ChangeInfo"})
     * @ORM\Column()
     */
    protected $company;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.phone", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @Assert\Regex(pattern="/^07([0-9]{8})$/", message="assert.valid.phone", groups={"flow_register_flow_step1", "ChangeInfo"})
     */
    protected $phone;

    /**
     *
     * @var string
     * @ORM\Column(name="no_employees", nullable = true, options={"default":0})
     */
    protected $noEmployees;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.bank", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @ORM\Column(nullable=true)
     */
    protected $bank;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.bank-account", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @ORM\Column(nullable=true)
     */
    protected $iban;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.company-id", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @ORM\Column(nullable=true)
     */
    protected $cui;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.company-number", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @Assert\Regex(pattern="/(J|F|C){1}[0-9]{2}\/[0-9]+\/(19|20)([0-9]{2})/", message="assert.valid.orc", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @ORM\Column(name="no_registration_orc", nullable=true)
     */
    protected $noRegistrationORC;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="no_certified_empowerment", nullable=true)
     */
    protected $noCertifiedEmpowerment;

    /**
     * @Assert\NotBlank(message="assert.required.county")
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\ROCounty")
     * @ORM\JoinColumn(name="county_id", referencedColumnName="id")
     */
    protected $county;

    /**
     * @Assert\NotBlank(message="assert.required.city")
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\ROCity")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $city;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.address", groups={"flow_register_flow_step1", "ChangeInfo"})
     * @ORM\Column(nullable=true)
     */
    protected $address;

    /**
     *
     * @var integer
     * @Assert\NotBlank(message="assert.required.function", groups={"flow_register_flow_step1", "AdminRegistration", "AdminProfile", "ChangeInfo"})
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    protected $function;

    /**
     *
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="profile", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $image;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    protected $demoAccount;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $deleted;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;


    /**
     * @ORM\OneToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function __construct()
    {
        $this->deleted = false;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Set noEmployees
     *
     * @param string $noEmployees
     * @return User
     */
    public function setNoEmployees($noEmployees)
    {
        $this->noEmployees = $noEmployees;

        return $this;
    }

    /**
     * Get noEmployees
     *
     * @return string
     */
    public function getNoEmployees()
    {
        return $this->noEmployees;
    }

    /**
     * Set bank
     *
     * @param string $bank
     * @return User
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set iban
     *
     * @param string $iban
     * @return User
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set cui
     *
     * @param string $cui
     * @return User
     */
    public function setCui($cui)
    {
        $this->cui = $cui;

        return $this;
    }

    /**
     * Get cui
     *
     * @return string
     */
    public function getCui()
    {
        return $this->cui;
    }

    /**
     * Set noRegistrationORC
     *
     * @param string $noRegistrationORC
     * @return User
     */
    public function setNoRegistrationORC($noRegistrationORC)
    {
        $this->noRegistrationORC = $noRegistrationORC;

        return $this;
    }

    /**
     * Get noRegistrationORC
     *
     * @return string
     */
    public function getNoRegistrationORC()
    {
        return $this->noRegistrationORC;
    }

    /**
     * Set noCertifiedEmpowerment
     *
     * @param string $noCertifiedEmpowerment
     * @return User
     */
    public function setNoCertifiedEmpowerment($noCertifiedEmpowerment)
    {
        $this->noCertifiedEmpowerment = $noCertifiedEmpowerment;

        return $this;
    }

    /**
     * Get noCertifiedEmpowerment
     *
     * @return string
     */
    public function getNoCertifiedEmpowerment()
    {
        return $this->noCertifiedEmpowerment;
    }

    /**
     * Set county
     *
     * @param \AppBundle\Entity\ROCounty $county
     * @return User
     */
    public function setCounty(\AppBundle\Entity\ROCounty $county = null)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get county
     *
     * @return \AppBundle\Entity\ROCounty
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\ROCity $city
     * @return User
     */
    public function setCity(\AppBundle\Entity\ROCity $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\ROCity
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set function
     *
     * @param integer $function
     * @return User
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     * @return integer
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     * @return User
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set demoAccount
     *
     * @param boolean $demoAccount
     * @return User
     */
    public function setDemoAccount($demoAccount)
    {
        $this->demoAccount = $demoAccount;

        return $this;
    }

    /**
     * Get demoAccount
     *
     * @return boolean
     */
    public function getDemoAccount()
    {
        return $this->demoAccount;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return SubDomain
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return SubDomain
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Profile
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return ($this->getId() ? "Profil" . " #" . $this->getId() : 'Create new');
    }

}
