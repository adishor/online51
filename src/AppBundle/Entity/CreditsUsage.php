<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CreditsUsage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CreditsUsageRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"document" = "DocumentCreditsUsage", "video" = "VideoCreditsUsage", "formular" = "FormularCreditsUsage", "egd" = "EgdFormularCreditsUsage"})
 * @HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class CreditsUsage
{

    const TYPE_DOCUMENT = 'document';
    const TYPE_FORMULAR = 'formular';
    const TYPE_VIDEO = 'video';
    const TYPE_EXPIRED = 'expired';

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
     *
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    protected $title;

    /**
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $credit;

    /**
     *
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    protected $mentions;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     *
     * @var \Application\Sonata\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="userCreditsUsage")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $user;

    /**
     *
     * @var \AppBundle\Entity\File
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\File", inversedBy="creditsUsage")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $file;


    /**
     *
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="creditsUsage", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;


    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expireDate;

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
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    protected $usageType;

    public function __construct()
    {
        $this->deleted = FALSE;
        $this->isFormConfigFinished = FALSE;
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
     * Set credit
     *
     * @param integer $credit
     * @return CreditsUsage
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
     * Set mentions
     *
     * @param string $mentions
     * @return CreditsUsage
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CreditsUsage
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
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return CreditsUsage
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

    public function isDocmumentValid()
    {

        $now = new \DateTime();
        $startDate = $this->createdAt();
        if ($now > $startDate->add(new \DateInterval('P' . $this->getDocument()->getValabilityDays() . 'D'))) {
            return true;
        }

        return false;
    }

    /**
     * Set expireDate
     *
     * @param \DateTime $expireDate
     * @return CreditsUsage
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
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
     * Set usageType
     *
     * @param string $usageType
     * @return CreditsUsage
     */
    public function setUsageType($usageType)
    {
        $this->usageType = $usageType;

        return $this;
    }

    /**
     * Get usageType
     *
     * @return string
     */
    public function getUsageType()
    {
        return $this->usageType;
    }

    /**
     * Set document
     *
     * @param File $file
     * @return CreditsUsage
     * @internal param Document $document
     */
    public function setFile(\AppBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get document
     *
     * @return \AppBundle\Entity\Document
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return CreditsUsage
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return ($this->getId() ? "Credit Usage" . " #" . $this->getId() : 'Create new');
    }

}
