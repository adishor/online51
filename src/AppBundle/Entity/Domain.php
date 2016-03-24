<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Domain
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Domain
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
     * var string
     *
     * @ORM\Column()
     */
    private $baseline;

    /**
     *
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Subscription", mappedBy="domains", cascade={"persist"})
     * @ORM\JoinTable(name="subscription_domain")
     */
    private $subscriptions;

    /**
     *
     * @ORM\OneToMany(targetEntity="SubDomain", mappedBy="domain", cascade={"persist", "remove"})
     */
    private $subdomains;

    public function __construct() {
        $this->subdomains = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
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
     * @return Domain
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
     * Set baseline
     *
     * @param string $baseline
     * @return Domain
     */
    public function setBaseline($baseline)
    {
        $this->baseline = $baseline;

        return $this;
    }

    /**
     * Get baseline
     *
     * @return string
     */
    public function getBaseline()
    {
        return $this->baseline;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Domain
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
     * Add subscriptions
     *
     * @param \AppBundle\Entity\Subscription $subscriptions
     * @return Domain
     */
    public function addSubscription(\AppBundle\Entity\Subscription $subscriptions)
    {
        $subscriptions->addDomain($this);
        $this->subscriptions[] = $subscriptions;

        return $this;
    }

    /**
     * Remove subscriptions
     *
     * @param \AppBundle\Entity\Subscription $subscriptions
     */
    public function removeSubscription(\AppBundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions->removeElement($subscriptions);
        $subscriptions->removeDomain($this);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Add subdomains
     *
     * @param \AppBundle\Entity\SubDomain $subdomains
     * @return Domain
     */
    public function addSubdomain(\AppBundle\Entity\SubDomain $subdomains)
    {
        $subdomains->setDomain($this);
        $this->subdomains[] = $subdomains;

        return $this;
    }

    /**
     * Remove subdomains
     *
     * @param \AppBundle\Entity\SubDomain $subdomains
     */
    public function removeSubdomain(\AppBundle\Entity\SubDomain $subdomains)
    {
        $this->subdomains->removeElement($subdomains);
    }

    /**
     * Get subdomains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubdomains()
    {
        return $this->subdomains;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection
     * @return \AppBundle\Entity\Domain
     */
    public function setSubdomains($subdomains)
    {
        if (count($subdomains) > 0) {
        foreach ($subdomains as $i) {
                $this->addSubdomain($i);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
