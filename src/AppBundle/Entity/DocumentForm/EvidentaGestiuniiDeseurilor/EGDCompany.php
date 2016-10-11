<?php

namespace AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;

class EGDCompany
{
    /**
     * @var type
     * @Type("string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     *
     * @var type
     * @Type("double")
     * @Assert\NotBlank()
     * @Assert\Type(type="double")
     * @Assert\Range(min = 0)
     */
    protected $cantitateDeseu;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCantitateDeseu()
    {
        return $this->cantitateDeseu;
    }

    public function setCantitateDeseu($cantitateDeseu)
    {
        $this->cantitateDeseu = $cantitateDeseu;
    }

}