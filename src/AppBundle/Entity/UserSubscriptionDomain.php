<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_subscription_domain")
 * @ORM\Entity()
 */
class UserSubscriptionDomain
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="userSubscriptionDomains")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="userSubscriptionDomains")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id", nullable=false)
     */
    private $domain;

    /**
     * @ORM\ManyToOne(targetEntity="UserSubscription", inversedBy="subscriptionDomains")
     * @ORM\JoinColumn(name="user_subscription_id", referencedColumnName="id")
     */
    private $userSubscription;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endingDate;

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return UserSubscriptionDomain
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
     * @return UserSubscriptionDomain
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
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return UserSubscriptionDomain
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
     * Set domain
     *
     * @param \AppBundle\Entity\Domain $domain
     * @return UserSubscriptionDomain
     */
    public function setDomain(\AppBundle\Entity\Domain $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return \AppBundle\Entity\Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set userSubscription
     *
     * @param \AppBundle\Entity\UserSubscription $userSubscription
     * @return UserSubscriptionDomain
     */
    public function setUserSubscription(\AppBundle\Entity\UserSubscription $userSubscription = null)
    {
        $this->userSubscription = $userSubscription;

        return $this;
    }

    /**
     * Get userSubscription
     *
     * @return \AppBundle\Entity\UserSubscription
     */
    public function getUserSubscription()
    {
        return $this->userSubscription;
    }

    public function __toString()
    {
        return $this->getUser() . "/" . $this->getDomain() . "(" .
                ($this->getUserSubscription() ? $this->getUserSubscription() : "NULL" ) . ")";
    }
}
