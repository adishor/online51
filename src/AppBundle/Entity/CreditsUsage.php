<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * CreditsUsage
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @HasLifecycleCallbacks
 */
class CreditsUsage
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
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $credit;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    protected $mentions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="userCreditsUsage")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="mediaCreditsUsage")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", nullable=false)
     */
    protected $document;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $documentExpireDate;

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

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->documentExpireDate = new \DateTime();
        $this->documentExpireDate->add(new \DateInterval('P' . $this->getDocument()->getValabilityDays() . 'D'));
    }

    /**
     * Set document
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $document
     * @return CreditsUsage
     */
    public function setDocument(\Application\Sonata\MediaBundle\Entity\Media $document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getDocument()
    {
        return $this->document;
    }

    public function isDocmumentValid()
    {

        $now = new \DateTime();
        $startDate = $this->createdAt();
        if ($now > $startDate->add(new \DateInterval('P' . $this->getDocument()->getValabilityDays() . 'D'))) {
            return true;
        }

        return FALSE;
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
     * Set documentExpireDate
     *
     * @param \DateTime $documentExpireDate
     * @return CreditsUsage
     */
    public function setDocumentExpireDate($documentExpireDate)
    {
        $this->documentExpireDate = $documentExpireDate;

        return $this;
    }

    /**
     * Get documentExpireDate
     *
     * @return \DateTime 
     */
    public function getDocumentExpireDate()
    {
        return $this->documentExpireDate;
    }
}
