<?php

namespace AppBundle\Entity\DocumentForm;

use JMS\Serializer\Annotation\Type;

class ConvocatorCSSMPunct
{
    /**
     *
     * @var type
     *
     * @Type("string")
     *
     */
    private $punctOrdineZi;

    public function getPunctOrdineZi()
    {
        return $this->punctOrdineZi;
    }

    public function setPunctOrdineZi($punctOrdineZi)
    {
        $this->punctOrdineZi = $punctOrdineZi;
    }

}