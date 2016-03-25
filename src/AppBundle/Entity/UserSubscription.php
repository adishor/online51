<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Table(name="user_subscription")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserSubscriptionRepository")
 * @HasLifecycleCallbacks
 */
class UserSubscription
{
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
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="userSubscriptions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Subscription", inversedBy="userSubscriptions")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id", nullable=false)
     */
    private $subscription;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default":0})
     */
    private $active;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
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
     * @ORM\OneToMany(targetEntity="UserSubscriptionDomain", mappedBy="userSubscription")
     */
    private $subscriptionDomains;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptionDomains = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return UserSubscription
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set active
     *
     * @param boolean $active
     * @return UserSubscription
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
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return UserSubscription
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
     * @return UserSubscription
     */
    public function setSubscription(\AppBundle\Entity\Subscription $subscription)
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return UserSubscription
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
     * @return UserSubscription
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
     * Set approvedBy
     *
     * @param \Application\Sonata\UserBundle\Entity\User $approvedBy
     * @return UserSubscription
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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Add subscriptionDomains
     *
     * @param \AppBundle\Entity\UserSubscriptionDomain $subscriptionDomains
     * @return UserSubscription
     */
    public function addSubscriptionDomain(\AppBundle\Entity\UserSubscriptionDomain $subscriptionDomains)
    {
        $this->subscriptionDomains[] = $subscriptionDomains;

        return $this;
    }

    /**
     * Remove subscriptionDomains
     *
     * @param \AppBundle\Entity\UserSubscriptionDomain $subscriptionDomains
     */
    public function removeSubscriptionDomain(\AppBundle\Entity\UserSubscriptionDomain $subscriptionDomains)
    {
        $this->subscriptionDomains->removeElement($subscriptionDomains);
    }

    /**
     * Get subscriptionDomains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptionDomains()
    {
        return $this->subscriptionDomains;
    }

    public function __toString() {

        return $this->getUser() . "/" . $this->getSubscription()
                . " (" . $this->getCreatedAt()->format('Y-m-d H:i:s') . ")";
    }
}
