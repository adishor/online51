<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 11/02/2017
 * Time: 14:30
 */
namespace AppBundle\Entity;

use Doctrine\Common\Inflector\Inflector;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"document" = "Document", "video" = "Video", "formular" = "Formular"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @CustomAssert\ValabilityForZeroCreditsValue
 */
abstract class File
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
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer")
     */
    private $creditValue;


    /**
     *
     * @var \AppBundle\Entity\CreditsUsage
     *
     * @ORM\OneToMany(targetEntity="CreditsUsage", mappedBy="file")
     */
    private $creditsUsage;

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
     * @var \AppBundle\Entity\Folder
     *
     * @ORM\ManyToOne(targetEntity="SubDomain", inversedBy="files")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    private $subdomain;

    /**
     *
     * @var \AppBundle\Entity\Folder
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="files")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="id")
     */
    private $folder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deleted = false;
        $this->creditsUsage = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Formular
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
     * @return Formular
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
     * @return Formular
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
     * @return Formular
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
     * @return Formular
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
     * Add formularCreditsUsage
     *
     * @param Document $creditsUsage
     * @return Formular
     * @internal param Document $formularCreditsUsage
     */
    public function addFormularCreditsUsage(\AppBundle\Entity\Document $creditsUsage)
    {
        $this->creditsUsage[] = $creditsUsage;

        return $this;
    }

    /**
     * Remove formularCreditsUsage
     *
     * @param Document $creditsUsage
     * @internal param Document $formularCreditsUsage
     */
    public function removeFormularCreditsUsage(\AppBundle\Entity\Document $creditsUsage)
    {
        $this->creditsUsage->removeElement($creditsUsage);
    }

    /**
     * Get formularCreditsUsage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditsUsage()
    {
        return $this->creditsUsage;
    }

    /**
     * Set subdomain
     *
     * @param Folder $folder
     * @return Folder
     * @internal param SubDomain $subdomain
     */
    public function setFolder(\AppBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return \AppBundle\Entity\SubDomain
     */
    public function getFolder()
    {
        return $this->folder;
    }


    /**
     * Set subdomain
     *
     * @param \AppBundle\Entity\SubDomain $subdomain
     * @return Formular
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

    public function getSluggableFields()
    {
        return ['name'];
    }

    public function generateSlugValue($values)
    {
        return Inflector::tableize(Inflector::classify(reset($values)));

    }

}