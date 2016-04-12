<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Document
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
    protected $title;

    /**
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $creditValue;

    /**
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $valabilityDays;

    /**
     * @ORM\ManyToOne(targetEntity="SubDomain", inversedBy="documents")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    private $subdomain;

    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set creditValue
     *
     * @param string $creditValue
     * @return Document
     */
    public function setCreditValue($creditValue)
    {
        $this->creditValue = $creditValue;

        return $this;
    }

    /**
     * Get creditValue
     *
     * @return string
     */
    public function getCreditValue()
    {
        return $this->creditValue;
    }

    /**
     * Set valability_days
     *
     * @param string $valabilityDays
     * @return Document
     */
    public function setValabilityDays($valabilityDays)
    {
        $this->valabilityDays = $valabilityDays;

        return $this;
    }

    /**
     * Get valability_days
     *
     * @return string
     */
    public function getValabilityDays()
    {
        return $this->valabilityDays;
    }

    /**
     * Set subdomain
     *
     * @param \AppBundle\Entity\SubDomain $subdomain
     * @return Document
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
}
