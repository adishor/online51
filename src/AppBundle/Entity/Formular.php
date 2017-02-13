<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/**
 * @ORM\Entity()
 */
class Formular extends File
{
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
     * @Assert\GreaterThanOrEqual(value = 0, message = "assert.at-least-0")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discountedCreditValue;

    /**
     * Set discountedCreditValue
     *
     * @param integer $discountedCreditValue
     * @return Formular
     */
    public function setDiscountedCreditValue($discountedCreditValue)
    {
        $this->discountedCreditValue = $discountedCreditValue;

        return $this;
    }

    /**
     * Get discountedCreditValue
     *
     * @return integer
     */
    public function getDiscountedCreditValue()
    {
        return $this->discountedCreditValue;
    }

}
