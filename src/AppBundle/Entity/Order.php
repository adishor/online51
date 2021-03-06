<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 * @HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Order
{
    /**
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer")
     */
    private $creditValue;

    /**
     *
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer")
     */
    private $valabilityDays;

    /**
     *
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer")
     */
    private $domainAmount;

    /**
     *
     * @var float
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="decimal", scale=2, nullable=true, options={"default":0})
     */
    private $price;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default":0})
     */
    private $active;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="first_active", type="boolean", nullable=false, options={"default":0})
     */
    private $firstActive;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="approvedSubscriptions")
     * @ORM\JoinColumn(name="approved_user_id", referencedColumnName="id")
     */
    private $approvedBy;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $approvedDate;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $mentions;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="modifiedSubscriptions")
     * @ORM\JoinColumn(name="last_modified_user_id", referencedColumnName="id")
     */
    private $lastModifiedBy;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endingDate;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Subscription", inversedBy="orders")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id", nullable=true)
     */
    private $subscription;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Domain", inversedBy="orders")
     * @ORM\JoinTable(name="order_domain")
     */
    private $domains;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $deleted;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     *
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="order",  orphanRemoval=true)
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    private $invoice;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deleted = FALSE;
        $this->domains = new ArrayCollection();
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
     * Set creditValue
     *
     * @param integer $creditValue
     * @return Order
     */
    public function setCreditValue($creditValue)
    {
        $this->creditValue = $creditValue;

        return $this;
    }

    /**
     * Get creditValue
     *
     * @return integer
     */
    public function getCreditValue()
    {
        return $this->creditValue;
    }

    /**
     * Set valabilityDays
     *
     * @param integer $valabilityDays
     * @return Order
     */
    public function setValabilityDays($valabilityDays)
    {
        $this->valabilityDays = $valabilityDays;

        return $this;
    }

    /**
     * Get valabilityDays
     *
     * @return integer
     */
    public function getValabilityDays()
    {
        return $this->valabilityDays;
    }

    /**
     * Set domainAmount
     *
     * @param integer $domainAmount
     * @return Order
     */
    public function setDomainAmount($domainAmount)
    {
        $this->domainAmount = $domainAmount;

        return $this;
    }

    /**
     * Get domainAmount
     *
     * @return integer
     */
    public function getDomainAmount()
    {
        return $this->domainAmount;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Order
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Order
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set firstActive
     *
     * @param boolean $firstActive
     * @return Order
     */
    public function setFirstActive($firstActive)
    {
        $this->firstActive = $firstActive;

        return $this;
    }

    /**
     * Get firstActive
     *
     * @return boolean
     */
    public function getFirstActive()
    {
        return $this->firstActive;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Order
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set approvedDate
     *
     * @param \DateTime $approvedDate
     * @return Order
     */
    public function setApprovedDate($approvedDate)
    {
        $this->approvedDate = $approvedDate;

        return $this;
    }

    /**
     * Get approvedDate
     *
     * @return \DateTime
     */
    public function getApprovedDate()
    {
        return $this->approvedDate;
    }

    /**
     * Set mentions
     *
     * @param string $mentions
     * @return Order
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;

        return $this;
    }

    /**
     * Get mentions
     *
     * @return string
     */
    public function getMentions()
    {
        return $this->mentions;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Order
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endingDate
     *
     * @param \DateTime $endingDate
     * @return Order
     */
    public function setEndingDate($endingDate)
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    /**
     * Get endingDate
     *
     * @return \DateTime
     */
    public function getEndingDate()
    {
        return $this->endingDate;
    }

    /**
     * Set approvedBy
     *
     * @param \Application\Sonata\UserBundle\Entity\User $approvedBy
     * @return Order
     */
    public function setApprovedBy(\Application\Sonata\UserBundle\Entity\User $approvedBy = null)
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    /**
     * Get approvedBy
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Order
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user)
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

    /**
     * Set subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     * @return Order
     */
    public function setSubscription(\AppBundle\Entity\Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \AppBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Add domains
     *
     * @param \AppBundle\Entity\Domain $domains
     * @return Order
     */
    public function addDomain(\AppBundle\Entity\Domain $domains)
    {
        $this->domains[] = $domains;

        return $this;
    }

    /**
     * Remove domains
     *
     * @param \AppBundle\Entity\Domain $domains
     */
    public function removeDomain(\AppBundle\Entity\Domain $domains)
    {
        $this->domains->removeElement($domains);
    }

    /**
     * Get domains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomains()
    {
        return $this->domains;
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
     * Set lastModifiedBy
     *
     * @param \Application\Sonata\UserBundle\Entity\User $lastModifiedBy
     * @return Order
     */
    public function setLastModifiedBy(\Application\Sonata\UserBundle\Entity\User $lastModifiedBy = null)
    {
        $this->lastModifiedBy = $lastModifiedBy;

        return $this;
    }

    /**
     * Get lastModifiedBy
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    public function __toString()
    {
        return ($this->getId() ? "Order" . " #" . $this->getId() : 'Create new');
    }


    /**
     * Set invoice
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $invoice
     * @return Order
     */
    public function setInvoice(\Application\Sonata\MediaBundle\Entity\Media $invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}
