<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Formular
{
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
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $field1;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field2;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field3;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field4;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field5;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field6;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field7;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field8;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field9;

    /**
     *
     * @var string JSON
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $field10;

    public function __construct()
    {
        $this->deleted = false;
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
     * Set field1
     *
     * @param array $field1
     * @return Formular
     */
    public function setField1($field1)
    {
        $this->field1 = $field1;

        return $this;
    }

    /**
     * Get field1
     *
     * @return array
     */
    public function getField1()
    {
        return $this->field1;
    }

    /**
     * Set field2
     *
     * @param array $field2
     * @return Formular
     */
    public function setField2($field2)
    {
        $this->field2 = $field2;

        return $this;
    }

    /**
     * Get field2
     *
     * @return array
     */
    public function getField2()
    {
        return $this->field2;
    }

    /**
     * Set field3
     *
     * @param array $field3
     * @return Formular
     */
    public function setField3($field3)
    {
        $this->field3 = $field3;

        return $this;
    }

    /**
     * Get field3
     *
     * @return array
     */
    public function getField3()
    {
        return $this->field3;
    }

    /**
     * Set field4
     *
     * @param array $field4
     * @return Formular
     */
    public function setField4($field4)
    {
        $this->field4 = $field4;

        return $this;
    }

    /**
     * Get field4
     *
     * @return array
     */
    public function getField4()
    {
        return $this->field4;
    }

    /**
     * Set field5
     *
     * @param array $field5
     * @return Formular
     */
    public function setField5($field5)
    {
        $this->field5 = $field5;

        return $this;
    }

    /**
     * Get field5
     *
     * @return array
     */
    public function getField5()
    {
        return $this->field5;
    }

    /**
     * Set field6
     *
     * @param array $field6
     * @return Formular
     */
    public function setField6($field6)
    {
        $this->field6 = $field6;

        return $this;
    }

    /**
     * Get field6
     *
     * @return array
     */
    public function getField6()
    {
        return $this->field6;
    }

    /**
     * Set field7
     *
     * @param array $field7
     * @return Formular
     */
    public function setField7($field7)
    {
        $this->field7 = $field7;

        return $this;
    }

    /**
     * Get field7
     *
     * @return array
     */
    public function getField7()
    {
        return $this->field7;
    }

    /**
     * Set field8
     *
     * @param array $field8
     * @return Formular
     */
    public function setField8($field8)
    {
        $this->field8 = $field8;

        return $this;
    }

    /**
     * Get field8
     *
     * @return array
     */
    public function getField8()
    {
        return $this->field8;
    }

    /**
     * Set field9
     *
     * @param array $field9
     * @return Formular
     */
    public function setField9($field9)
    {
        $this->field9 = $field9;

        return $this;
    }

    /**
     * Get field9
     *
     * @return array
     */
    public function getField9()
    {
        return $this->field9;
    }

    /**
     * Set field10
     *
     * @param array $field10
     * @return Formular
     */
    public function setField10($field10)
    {
        $this->field10 = $field10;

        return $this;
    }

    /**
     * Get field10
     *
     * @return array
     */
    public function getField10()
    {
        return $this->field10;
    }
}
