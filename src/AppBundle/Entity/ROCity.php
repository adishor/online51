<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ROCity
 *
 * @ORM\Table(name="ro_city")
 * @ORM\Entity
 */
class ROCity
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
     * @ORM\ManyToOne(targetEntity="ROCounty", inversedBy="cities")
     * @ORM\JoinColumn(name="county_id", referencedColumnName="id")
     */
    private $county;

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
     * @return ROCity
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
     * Set county
     *
     * @param \AppBundle\Entity\ROCounty $county
     * @return ROCity
     */
    public function setCounty(\AppBundle\Entity\ROCounty $county = null)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get county
     *
     * @return \AppBundle\Entity\ROCounty
     */
    public function getCounty()
    {
        return $this->county;
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
        return $this->getName();
    }

}
