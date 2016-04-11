<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subscription
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Subscription
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
     * @var string
     *
     * @ORM\Column()
     */
    private $name;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $intro;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     *
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer")
     */
    private $credit;

    /**
     *
     * @var integer
     *
     * @Assert\Range(min = 1, max = 5,
     *                  minMessage = "subscription.valability.min",
     *                  maxMessage = "subscription.valability.max" )
     * @ORM\Column(type="integer")
     */
    private $valability;

    /**
     *
     * @var \AppBundle\Entity\Domain
     *
     * @ORM\ManyToMany(targetEntity="Domain", inversedBy="subscriptions")
     */
    private $domains;

    /**
     * @ORM\OneToMany(targetEntity="UserSubscription", mappedBy="subscription", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $userSubscriptions;

    public function __construct() {
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
     * Set name
     *
     * @param string $name
     * @return Subscription
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
     * Set intro
     *
     * @param string $intro
     * @return Subscription
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Subscription
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set domainAmount
     *
     * @param integer $domainAmount
     * @return Subscription
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
     * @return Subscription
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
     * Set credit
     *
     * @param integer $credit
     * @return Subscription
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set valability
     *
     * @param integer $valability
     * @return Subscription
     */
    public function setValability($valability)
    {
        $this->valability = $valability;

        return $this;
    }

    /**
     * Get valability
     *
     * @return integer
     */
    public function getValability()
    {
        return $this->valability;
    }

    /**
     * Add domains
     *
     * @param \AppBundle\Entity\Domain $domains
     * @return Subscription
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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Add userSubscriptions
     *
     * @param \AppBundle\Entity\UserSubscription $userSubscriptions
     * @return Subscription
     */
    public function addUserSubscription(\AppBundle\Entity\UserSubscription $userSubscriptions)
    {
        $this->userSubscriptions[] = $userSubscriptions;

        return $this;
    }

    /**
     * Remove userSubscriptions
     *
     * @param \AppBundle\Entity\UserSubscription $userSubscriptions
     */
    public function removeUserSubscription(\AppBundle\Entity\UserSubscription $userSubscriptions)
    {
        $this->userSubscriptions->removeElement($userSubscriptions);
    }

    /**
     * Get userSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserSubscriptions()
    {
        return $this->userSubscriptions;
    }
}
