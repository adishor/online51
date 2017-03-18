<?php

namespace AppBundle\Entity;

use Doctrine\Common\Inflector\Inflector;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Domain
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DomainRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("name", message="assert.unique.name")
 */
class Domain
{

    use ORMBehaviors\Sluggable\Sluggable;
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
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * var string
     *
     * @ORM\Column(nullable=true)
     */
    private $baseline;

    /**
     *
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $dedicated;

    /**
     *
     * @ORM\OneToMany(targetEntity="SubDomain", mappedBy="domain", cascade={"persist"})
     */
    private $subdomains;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    private $demoDomain;

    /**
     *
     * @var integer
     *
     * @ORM\Column(nullable=true)
     */
    private $demoCreditValue;

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

    public function __construct()
    {
        $this->subdomains = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->deleted = FALSE;
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
     * Set dedicated
     *
     * @param boolean $dedicated
     * @return Domain
     */
    public function setDedicated($dedicated)
    {
        $this->dedicated = $dedicated;

        return $this;
    }

    /**
     * Get dedicated
     *
     * @return boolean
     */
    public function getDedicated()
    {
        return $this->dedicated;
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
     * Set demoDomain
     *
     * @param boolean $demoDomain
     * @return Domain
     */
    public function setDemoDomain($demoDomain)
    {
        $this->demoDomain = $demoDomain;

        return $this;
    }

    /**
     * Get demoDomain
     *
     * @return boolean
     */
    public function getDemoDomain()
    {
        return $this->demoDomain;
    }

    /**
     * Set demoCreditValue
     *
     * @param string $demoCreditValue
     * @return Domain
     */
    public function setDemoCreditValue($demoCreditValue)
    {
        $this->demoCreditValue = $demoCreditValue;

        return $this;
    }

    /**
     * Get demoCreditValue
     *
     * @return string
     */
    public function getDemoCreditValue()
    {
        return $this->demoCreditValue;
    }

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

    public function getSluggableFields()
    {
        return ['name'];
    }

    public function generateSlugValue($values)
    {
        return Inflector::tableize(Inflector::classify(reset($values)));
    }
}
