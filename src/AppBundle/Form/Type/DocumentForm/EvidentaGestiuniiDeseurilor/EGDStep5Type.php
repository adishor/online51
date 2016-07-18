<?php

namespace AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGD4EliminareDeseuriType;

class EGDStep5Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('EGD4EliminareDeseuri', CollectionType::class, array(
            'entry_type' => EGD4EliminareDeseuriType::class,
        ));
    }

    public function getName()
    {
        return 'egd_step5';
    }

}