<?php

namespace AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGD3ValorificareDeseuriType;

class EGDStep4Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('EGD3ValorificareDeseuri', CollectionType::class, array(
            'entry_type' => EGD3ValorificareDeseuriType::class,
        ));
    }

    public function getName()
    {
        return 'egd_step4';
    }

}