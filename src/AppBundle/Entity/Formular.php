<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @CustomAssert\ValabilityFormular
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Formular
{

    use ORMBehaviors\Sluggable\Sluggable;

    const MONTH_JANUARY = 1;
    const MONTH_FEBRUARY = 2;
    const MONTH_MARCH = 3;
    const MONTH_APRIL = 4;
    const MONTH_MAY = 5;
    const MONTH_JUNE = 6;
    const MONTH_JULY = 7;
    const MONTH_AUGUST = 8;
    const MONTH_SEPTEMBER = 9;
    const MONTH_OCTOMBER = 10;
    const MONTH_NOVEMBER = 11;
    const MONTH_DECEMBER = 12;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valabilityMonth;

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
     * @var \AppBundle\Entity\CreditsUsage
     *
     * @ORM\OneToMany(targetEntity="CreditsUsage", mappedBy="formular")
     */
    private $formularCreditsUsage;

    /**
     *
     * @var \AppBundle\Entity\SubDomain
     *
     * @ORM\ManyToOne(targetEntity="SubDomain", inversedBy="formulars")
     * @ORM\JoinColumn(name="subdomain_id", referencedColumnName="id")
     */
    private $subdomain;

    public function __construct()
    {
        $this->deleted = false;
        $this->formularCreditsUsage = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set valabilityMonth
     *
     * @param integer $valabilityMonth
     * @return Formular
     */
    public function setValabilityMonth($valabilityMonth)
    {
        $this->valabilityMonth = $valabilityMonth;

        return $this;
    }

    /**
     * Get valabilityMonth
     *
     * @return integer
     */
    public function getValabilityMonth()
    {
        return $this->valabilityMonth;
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
     * @param \AppBundle\Entity\Document $formularCreditsUsage
     * @return Formular
     */
    public function addFormularCreditsUsage(\AppBundle\Entity\Document $formularCreditsUsage)
    {
        $this->formularCreditsUsage[] = $formularCreditsUsage;

        return $this;
    }

    /**
     * Remove formularCreditsUsage
     *
     * @param \AppBundle\Entity\Document $formularCreditsUsage
     */
    public function removeFormularCreditsUsage(\AppBundle\Entity\Document $formularCreditsUsage)
    {
        $this->formularCreditsUsage->removeElement($formularCreditsUsage);
    }

    /**
     * Get formularCreditsUsage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormularCreditsUsage()
    {
        return $this->formularCreditsUsage;
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
        return str_replace(array("/", " ", "-"), "_", implode('-', $values));
    }

}
