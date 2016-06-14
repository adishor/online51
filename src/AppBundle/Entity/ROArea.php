<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ROArea
 *
 * @ORM\Table(name="ro_area")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ROAreaRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ROArea
{

    use ORMBehaviors\Sluggable\Sluggable;
    /**
     *
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
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
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="ROCounty", inversedBy="areas")
     */
    private $counties;

    /**
     *
     * @var \AppBundle\Entity\Ad
     *
     * @ORM\ManyToMany(targetEntity="Ad", inversedBy="areas")
     */
    private $ads;

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
        $this->counties = new ArrayCollection();
        $this->ads = new ArrayCollection();
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
     * @return ROArea
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
     * Set deleted
     *
     * @param boolean $deleted
     * @return ROArea
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
     * @return ROArea
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
     * Add counties
     *
     * @param \AppBundle\Entity\ROCounty $counties
     * @return ROArea
     */
    public function addCounty(\AppBundle\Entity\ROCounty $counties)
    {
        $this->counties[] = $counties;

        return $this;
    }

    /**
     * Remove counties
     *
     * @param \AppBundle\Entity\ROCounty $counties
     */
    public function removeCounty(\AppBundle\Entity\ROCounty $counties)
    {
        $this->counties->removeElement($counties);
    }

    /**
     * Get counties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCounties()
    {
        return $this->counties;
    }

    /**
     * Add ads
     *
     * @param \AppBundle\Entity\Ad $ads
     * @return ROArea
     */
    public function addAd(\AppBundle\Entity\Ad $ads)
    {
        $this->ads[] = $ads;

        return $this;
    }

    /**
     * Remove ads
     *
     * @param \AppBundle\Entity\Ad $ads
     */
    public function removeAd(\AppBundle\Entity\Ad $ads)
    {
        $this->ads->removeElement($ads);
    }

    /**
     * Get ads
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAds()
    {
        return $this->ads;
    }

    public function getSluggableFields()
    {
        return [ 'name'];
    }

    public function generateSlugValue($values)
    {
        return strtolower(str_replace(array("/", " "), array("-", ""), implode('-', $values)));
    }

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

}