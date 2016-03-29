<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ROCounty
 *
 * @ORM\Table(name="ro_county")
 * @ORM\Entity
 */
class ROCounty
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
     */
    private $name;

    /**
     *
     * @var string
     *
     * @ORM\OneToMany(targetEntity="ROCity", mappedBy="county", cascade={"persist", "remove"})
     */
    private $cities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new ArrayCollection();
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
     * @return ROCounty
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
     * Add cities
     *
     * @param \AppBundle\Entity\ROCity $cities
     * @return ROCounty
     */
    public function addCity(\AppBundle\Entity\ROCity $cities)
    {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove cities
     *
     * @param \AppBundle\Entity\ROCity $cities
     */
    public function removeCity(\AppBundle\Entity\ROCity $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }

    public function getSluggableFields()
    {
        return [ 'name' ];
    }

    public function generateSlugValue($values)
    {
        return strtolower(str_replace(array("/", " "), array("-", ""), implode('-', $values)));
    }

    public function __toString()
    {
        return $this->getName();
    }
}
