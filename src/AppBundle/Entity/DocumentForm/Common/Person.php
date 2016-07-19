<?php

namespace AppBundle\Entity\DocumentForm\Common;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class Person
{
    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    private $gender;

    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var type
     * @Type("string")
     */
    private $function;

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function setFunction($function)
    {
        $this->function = $function;
    }

}