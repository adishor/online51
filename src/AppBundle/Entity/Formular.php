<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Formular
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
     * @var integer
     *
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer")
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
     * @var \AppBundle\Entity\CreditsUsage
     * @ORM\OneToMany(targetEntity="Document", mappedBy="formular")
     */
    private $formularCreditsUsage;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $configUniqueness;

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
     * Set configUniqueness
     *
     * @param array $configUniqueness
     * @return Formular
     */
    public function setConfigUniqueness($configUniqueness)
    {
        $this->configUniqueness = $configUniqueness;

        return $this;
    }

    /**
     * Get configUniqueness
     *
     * @return array
     */
    public function getConfigUniqueness()
    {
        return $this->configUniqueness;
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
