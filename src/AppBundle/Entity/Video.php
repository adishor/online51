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
class Video
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
     *
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="video", orphanRemoval=true)
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    private $media;

    /**
     *
     * @var \AppBundle\Entity\CreditsUsage
     *
     * @ORM\OneToMany(targetEntity="CreditsUsage", mappedBy="video")
     * @ORM\JoinColumn(name="creditsUsage_id", referencedColumnName="id")
     */
    private $videoCreditsUsage;

    /**
     *
     * @var \AppBundle\Entity\SubDomain
     *
     * @ORM\ManyToOne(targetEntity="SubDomain", inversedBy="videos")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    private $subdomain;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deleted = false;
        $this->videoCreditsUsage = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * Add videoCreditsUsage
     *
     * @param \AppBundle\Entity\CreditsUsage $videoCreditsUsage
     * @return Video
     */
    public function addVideoCreditsUsage(\AppBundle\Entity\CreditsUsage $videoCreditsUsage)
    {
        $this->videoCreditsUsage[] = $videoCreditsUsage;

        return $this;
    }

    /**
     * Remove videoCreditsUsage
     *
     * @param \AppBundle\Entity\CreditsUsage $videoCreditsUsage
     */
    public function removeVideoCreditsUsage(\AppBundle\Entity\CreditsUsage $videoCreditsUsage)
    {
        $this->videoCreditsUsage->removeElement($videoCreditsUsage);
    }

    /**
     * Get videoCreditsUsage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideoCreditsUsage()
    {
        return $this->videoCreditsUsage;
    }

    /**
     * Set subdomain
     *
     * @param \AppBundle\Entity\SubDomain $subdomain
     * @return Video
     */
    public function setSubdomain(\AppBundle\Entity\SubDomain $subdomain = null)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return \AppBundle\Entity\SubDomain
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

}
