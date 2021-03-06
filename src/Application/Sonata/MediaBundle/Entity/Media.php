<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;

/**
 * This file has been generated by the Sonata EasyExtends bundle.
 *
 * @link https://sonata-project.org/bundles/easy-extends
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Media extends BaseMedia
{
    const DOCUMENT_TYPE = 'Document';
    const INVOICE_TYPE = 'Factura proforma';
    const FORM_GENERATED_TYPE = 'Document generat de formular';
    const IMAGE_TYPE = 'Imagine';
    const VIDEO_TYPE = 'Video';

    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var boolean
     */
    private $deleted;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var AppBundle\Entity\Document
     */
    private $document;

    /**
     * @var AppBundle\Entity\Video
     */
    private $video;

    /**
     * @var AppBundle\Entity\Order
     */
    private $order;

    /**
     * @var AppBundle\Entity\Ad
     */
    private $ad;

    /**
     * @var AppBundle/Entity/Profile
     */
    private $profile;

    /**
     * @var string
     */
    private $mediaType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galleryHasMedias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creditsUsage = new \Doctrine\Common\Collections\ArrayCollection();
        $this->deleted = FALSE;
    }

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add galleryHasMedias
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     * @return Media
     */
    public function addGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias)
    {
        $this->galleryHasMedias[] = $galleryHasMedias;

        return $this;
    }

    /**
     * Remove galleryHasMedias
     *
     * @param \Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     */
    public function removeGalleryHasMedia(\Application\Sonata\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias)
    {
        $this->galleryHasMedias->removeElement($galleryHasMedias);
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Media
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
     * @return Media
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
     * Set document
     *
     * @param \AppBundle\Entity\Document $document
     * @return Media
     */
    public function setDocument(\AppBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \AppBundle\Entity\Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set video
     *
     * @param \AppBundle\Entity\Video $video
     * @return Media
     */
    public function setVideo(\AppBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \AppBundle\Entity\Video
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set mediaType
     *
     * @param string $mediaType
     * @return Media
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get mediaType
     *
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Order $order
     * @return Media
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set ad
     *
     * @param \AppBundle\Entity\Ad $ad
     * @return Media
     */
    public function setAd(\AppBundle\Entity\Ad $ad = null)
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return \AppBundle\Entity\Ad
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * Set profile
     *
     * @param \AppBundle\Entity\Profile $profile
     * @return Media
     */
    public function setProfile(\AppBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AppBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @var \AppBundle\Entity\CreditsUsage
     */
    private $creditsUsage;

    /**
     * Set creditsUsage
     *
     * @param \AppBundle\Entity\CreditsUsage $creditsUsage
     * @return Media
     */
    public function setCreditsUsage(\AppBundle\Entity\CreditsUsage $creditsUsage = null)
    {
        $this->creditsUsage = $creditsUsage;

        return $this;
    }

    /**
     * Get creditsUsage
     *
     * @return \AppBundle\Entity\CreditsUsage
     */
    public function getCreditsUsage()
    {
        return $this->creditsUsage;
    }

    public function setBinaryContent($binaryContent)
    {
        if ($this->providerReference) {
            $this->previousProviderReference = $this->providerReference;
        }
        $this->providerReference = null;
        $this->binaryContent = $binaryContent;
        if (!$this->providerReference && $this->previousProviderReference) {
            $this->providerReference = $this->previousProviderReference;
        }
    }

}