<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * This file has been generated by the Sonata EasyExtends bundle.
 *
 * @link https://sonata-project.org/bundles/easy-extends
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */

/**
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity()
 * @UniqueEntity("email", message="assert.unique.email", groups={"flow_register_flow_step1"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User extends BaseUser
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
     * @var string
     * @Assert\NotBlank(message="user.required.email", groups={"flow_register_flow_step1", "AdminRegistration", "AdminProfile", "ChangeInfo"})
     * @Assert\Email(message="assert.valid.email", groups={"flow_register_flow_step1", "AdminRegistration", "AdminProfile", "ChangeInfo"})
     */
    protected $email;

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
     * @Assert\NotBlank(message="assert.required.password", groups={"flow_register_flow_step1", "resetPassword"})
     * @Assert\Length(min=6, minMessage="assert.password.length", groups={"flow_register_flow_step1", "ChangePassword", "resetPassword"})
     */
    protected $password;

    /**
     *
     * @var string
     * @Assert\Length(min=6, minMessage="assert.password.length")
     * @Assert\NotBlank(message="assert.required.password")
     */
    protected $plainPassword;

    /**
     *
     * @var string
     * @Assert\NotBlank(message="assert.required.username")
     */
    protected $username;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $creditsTotal;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastCreditUpdate;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Order", mappedBy="user")
     */
    protected $orders;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Order", mappedBy="approvedBy")
     */
    protected $approvedSubscriptions;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Order", mappedBy="lastModifiedBy")
     */
    protected $modifiedSubscriptions;

    /**
     *
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\CreditsUsage", mappedBy="user")
     */
    protected $userCreditsUsage;

    /**
     *
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="user", orphanRemoval=true, cascade={"persist"})
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

    protected $registerSubscriptionId;
    protected $registerDomainIds;

    public function __construct()
    {
        $this->deleted = FALSE;
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
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
     * Set creditsTotal
     *
     * @param integer $creditsTotal
     * @return User
     */
    public function setCreditsTotal($creditsTotal)
    {
        $this->creditsTotal = $creditsTotal;

        return $this;
    }

    /**
     * Get creditsTotal
     *
     * @return integer
     */
    public function getCreditsTotal()
    {
        return $this->creditsTotal;
    }

    /**
     * Add orders
     *
     * @param \AppBundle\Entity\Order $orders
     * @return User
     */
    public function addOrder(\AppBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \AppBundle\Entity\Order $orders
     */
    public function removeOrder(\AppBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add approvedSubscriptions
     *
     * @param \AppBundle\Entity\Order $approvedSubscriptions
     * @return User
     */
    public function addApprovedSubscription(\AppBundle\Entity\Order $approvedSubscriptions)
    {
        $this->approvedSubscriptions[] = $approvedSubscriptions;

        return $this;
    }

    /**
     * Remove approvedSubscriptions
     *
     * @param \AppBundle\Entity\Order $approvedSubscriptions
     */
    public function removeApprovedSubscription(\AppBundle\Entity\Order $approvedSubscriptions)
    {
        $this->approvedSubscriptions->removeElement($approvedSubscriptions);
    }

    /**
     * Get approvedSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApprovedSubscriptions()
    {
        return $this->approvedSubscriptions;
    }

    /**
     * Add userCreditsUsage
     *
     * @param \AppBundle\Entity\CreditsUsage $userCreditsUsage
     * @return User
     */
    public function addUserCreditsUsage(\AppBundle\Entity\CreditsUsage $userCreditsUsage)
    {
        $this->userCreditsUsage[] = $userCreditsUsage;

        return $this;
    }

    /**
     * Remove userCreditsUsage
     *
     * @param \AppBundle\Entity\CreditsUsage $userCreditsUsage
     */
    public function removeUserCreditsUsage(\AppBundle\Entity\CreditsUsage $userCreditsUsage)
    {
        $this->userCreditsUsage->removeElement($userCreditsUsage);
    }

    /**
     * Get userCreditsUsage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserCreditsUsage()
    {
        return $this->userCreditsUsage;
    }

    /**
     * Set lastCreditUpdate
     *
     * @param \DateTime $lastCreditUpdate
     * @return User
     */
    public function setLastCreditUpdate($lastCreditUpdate)
    {
        $this->lastCreditUpdate = $lastCreditUpdate;

        return $this;
    }

    /**
     * Get lastCreditUpdate
     *
     * @return \DateTime
     */
    public function getLastCreditUpdate()
    {
        return $this->lastCreditUpdate;
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
     * Add modifiedSubscriptions
     *
     * @param \AppBundle\Entity\Order $modifiedSubscriptions
     * @return User
     */
    public function addModifiedSubscription(\AppBundle\Entity\Order $modifiedSubscriptions)
    {
        $this->modifiedSubscriptions[] = $modifiedSubscriptions;

        return $this;
    }

    /**
     * Remove modifiedSubscriptions
     *
     * @param \AppBundle\Entity\Order $modifiedSubscriptions
     */
    public function removeModifiedSubscription(\AppBundle\Entity\Order $modifiedSubscriptions)
    {
        $this->modifiedSubscriptions->removeElement($modifiedSubscriptions);
    }

    /**
     * Get modifiedSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModifiedSubscriptions()
    {
        return $this->modifiedSubscriptions;
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

    public function getRegisterSubscriptionId()
    {
        return $this->registerSubscriptionId;
    }

    public function getRegisterDomainIds()
    {
        return $this->registerDomainIds;
    }

    public function setRegisterSubscriptionId($registerSubscriptionId)
    {
        $this->registerSubscriptionId = $registerSubscriptionId;
    }

    public function setRegisterDomainIds($registerDomainIds)
    {
        $this->registerDomainIds = $registerDomainIds;
    }

}