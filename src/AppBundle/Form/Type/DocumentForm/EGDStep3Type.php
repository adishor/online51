<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\EGD2StocareTratareTransportDeseuriType;

class EGDStep3Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('EGD2StocareTratareTransportDeseuri', CollectionType::class, array(
            'entry_type' => EGD2StocareTratareTransportDeseuriType::class,
        ));
    }

    public function getName()
    {
        return 'egd_step3';
    }

}