<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdsRepository")
 */
class Ad
{
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
     * @ORM\Column()
     */
    protected $name;

    /**
     *
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", inversedBy="ad", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $image;

    /**
     *
     * @ORM\ManyToMany(targetEntity="ROArea", mappedBy="ads", cascade={"persist"})
     * @ORM\JoinTable(name="roarea_ad")
     */
    protected $areas;


    /**
     *
     * @var string
     *
     * @ORM\Column()
     */
    protected $url;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->areas = new ArrayCollection();
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
     * Set image
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $image
     * @return Ad
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Ad
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
     * Add areas
     *
     * @param \AppBundle\Entity\ROArea $areas
     * @return Ad
     */
    public function addArea(\AppBundle\Entity\ROArea $areas)
    {
        $areas->addAd($this);
        $this->areas[] = $areas;

        return $this;
    }

    /**
     * Remove areas
     *
     * @param \AppBundle\Entity\ROArea $areas
     */
    public function removeArea(\AppBundle\Entity\ROArea $areas)
    {
        $this->areas->removeElement($areas);
        $areas->removeAd($this);
    }

    /**
     * Get areas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    public function __toString()
    {
        return ($this->getId() ? $this->getName() : 'Create new');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}
