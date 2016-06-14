<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\EGD1GenerareDeseuriType;

class EGDStep2Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('EGD1GenerareDeseuri', CollectionType::class, array(
            'entry_type' => EGD1GenerareDeseuriType::class,
        ));
    }

    public function getName()
    {
        return 'egd_step2';
    }

}