<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @CustomAssert\ValabilityForZeroCreditsValue
 */
class Document
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
     * @Assert\NotBlank()
     */
    private $name;

    /**
     *
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $creditValue;

    /**
     *
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valabilityDays;

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
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="document", orphanRemoval=true)
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    private $media;

    /**
     *
     * @var \AppBundle\Entity\CreditsUsage
     *
     * @ORM\OneToMany(targetEntity="CreditsUsage", mappedBy="document")
     * @ORM\JoinColumn(name="creditsUsage_id", referencedColumnName="id")
     */
    private $documentCreditsUsage;

    /**
     *
     * @var \AppBundle\Entity\SubDomain
     *
     * @ORM\ManyToOne(targetEntity="SubDomain", inversedBy="documents")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    private $subdomain;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deleted = false;
        $this->documentCreditsUsage = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Document
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
     * Set creditValue
     *
     * @param integer $creditValue
     * @return Document
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
     * @return Document
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
     * Set deleted
     *
     * @param boolean $deleted
     * @return Document
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
     * @return Document
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
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return Document
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add documentCreditsUsage
     *
     * @param \AppBundle\Entity\Document $documentCreditsUsage
     * @return Document
     */
    public function addDocumentCreditsUsage(\AppBundle\Entity\Document $documentCreditsUsage)
    {
        $this->documentCreditsUsage[] = $documentCreditsUsage;

        return $this;
    }

    /**
     * Remove documentCreditsUsage
     *
     * @param \AppBundle\Entity\Document $documentCreditsUsage
     */
    public function removeDocumentCreditsUsage(\AppBundle\Entity\Document $documentCreditsUsage)
    {
        $this->documentCreditsUsage->removeElement($documentCreditsUsage);
    }

    /**
     * Get documentCreditsUsage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentCreditsUsage()
    {
        return $this->documentCreditsUsage;
    }

    /**
     * Add subdomain
     *
     * @param \AppBundle\Entity\SubDomain $subdomain
     * @return Document
     */
    public function addSubdomain(\AppBundle\Entity\SubDomain $subdomain)
    {
        $this->subdomain[] = $subdomain;

        return $this;
    }

    /**
     * Remove subdomain
     *
     * @param \AppBundle\Entity\SubDomain $subdomain
     */
    public function removeSubdomain(\AppBundle\Entity\SubDomain $subdomain)
    {
        $this->subdomain->removeElement($subdomain);
    }

    /**
     * Get subdomain
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Set subdomain
     *
     * @param \AppBundle\Entity\SubDomain $subdomain
     * @return Document
     */
    public function setSubdomain(\AppBundle\Entity\SubDomain $subdomain = null)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

}
