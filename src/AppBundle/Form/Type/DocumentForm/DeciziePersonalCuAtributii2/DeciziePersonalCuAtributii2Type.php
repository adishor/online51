<?php

namespace AppBundle\Form\Type\DocumentForm\DeciziePersonalCuAtributii2;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class DeciziePersonalCuAtributii2Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('company', TextType::class)
          ->add('administrator', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('workersAttributions', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
        ;
    }

    public function getName()
    {
        return 'decizie_personal_cu_atributii_2_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}