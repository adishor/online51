<?php

namespace AppBundle\Entity;

use Doctrine\Common\Inflector\Inflector;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SubDomain
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubDomainRepository")
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
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="subdomain")
     */
    private $folders;

    /**
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="subdomain")
     */
    private $files;

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
        $this->folders = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param Folder $folder
     * @return SubDomain
     * @internal param \Application\Sonata\MediaBundle\Entity\Media $documents
     */
    public function addFolder(Folder $folder)
    {
        $this->folders[] = $folder;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param Folder $folder
     * @internal param \Application\Sonata\MediaBundle\Entity\Media $documents
     */
    public function removeFolder(Folder $folder)
    {
        $this->folders->removeElement($folder);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * Add documents
     *
     * @param Folder $folder
     * @return SubDomain
     * @internal param \Application\Sonata\MediaBundle\Entity\Media $documents
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param Folder $folder
     * @internal param \Application\Sonata\MediaBundle\Entity\Media $documents
     */
    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
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
        return ($this->getId() ? $this->getDomain()->getName() . ' - ' . $this->getName() : 'Create new');
    }

    public function getSluggableFields()
    {
        return [ 'name'];
    }

    public function generateSlugValue($values)
    {
        return Inflector::tableize(Inflector::classify(reset($values)));
    }

}
