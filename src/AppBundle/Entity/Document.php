<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
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
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;

    /**
     *
     * @var \AppBundle\Entity\CreditsUsage
     * @ORM\OneToMany(targetEntity="Document", mappedBy="document")
     */
    private $documentCreditsUsage;

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

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

}
