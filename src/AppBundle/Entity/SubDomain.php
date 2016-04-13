<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubDomain
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SubDomain
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
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="subdomains")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     */
    private $domain;

    /**
     *
     * @ORM\OneToMany(targetEntity="Application\Sonata\MediaBundle\Entity\Media", mappedBy="subdomain", cascade={"persist", "remove"})
     */
    private $documents;

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
     * @return SubDomain
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
     * Set description
     *
     * @param string $description
     * @return SubDomain
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
     * Set domain
     *
     * @param \AppBundle\Entity\Domain $domain
     * @return SubDomain
     */
    public function setDomain(\AppBundle\Entity\Domain $domain = null)
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

    public function __toString()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add documents
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $documents
     * @return SubDomain
     */
    public function addDocument(\Application\Sonata\MediaBundle\Entity\Media $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $documents
     */
    public function removeDocument(\Application\Sonata\MediaBundle\Entity\Media $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
