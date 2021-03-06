<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SubDomain
 *
 * @ORM\Table()
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("name", message="assert.unique.name")
 */
class SubDomain
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
     * @ORM\OneToMany(targetEntity="Document", mappedBy="subdomain")
     */
    private $documents;

    /**
     *
     * @ORM\OneToMany(targetEntity="Video", mappedBy="subdomain")
     */
    private $videos;

    /**
     *
     * @ORM\OneToMany(targetEntity="Formular", mappedBy="subdomain")
     */
    private $formulars;

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
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulars = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add videos
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $videos
     * @return SubDomain
     */
    public function addVideo(\Application\Sonata\MediaBundle\Entity\Media $videos)
    {
        $this->videos[] = $videos;

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $videos
     */
    public function removeVideo(\Application\Sonata\MediaBundle\Entity\Media $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add formulars
     *
     * @param \AppBundle\Entity\Formular $formulars
     * @return SubDomain
     */
    public function addFormular(\AppBundle\Entity\Formular $formulars)
    {
        $this->formulars[] = $formulars;

        return $this;
    }

    /**
     * Remove formulars
     *
     * @param \AppBundle\Entity\Formular $formulars
     */
    public function removeFormular(\AppBundle\Entity\Formular $formulars)
    {
        $this->formulars->removeElement($formulars);
    }

    /**
     * Get formulars
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulars()
    {
        return $this->formulars;
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

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

    public function getSluggableFields()
    {
        return [ 'name'];
    }

    public function generateSlugValue($values)
    {
        return strtolower(str_replace(array("/", " "), array("-", ""), implode('-', $values)));
    }

}
