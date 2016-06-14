<?php

namespace AppBundle\Entity\DocumentForm;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGDCompany
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
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     *
     * @var type
     *
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $startMonth;

    public function getName()
    {
        return $this->name;
    }

    public function getStartMonth()
    {
        return $this->startMonth;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setStartMonth($startMonth)
    {
        $this->startMonth = $startMonth;
    }

}