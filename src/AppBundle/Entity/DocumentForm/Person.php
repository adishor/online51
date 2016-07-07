<?php

namespace AppBundle\Entity\DocumentForm;

use JMS\Serializer\Annotation\Type;

class Person
{
    /**
     *
     * @var type
     *
     * @Type("string")
     *
     */
    private $name;

    /**
     *
     * @var type
     *
     * @Type("string")
     *
     */
    private $function;

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