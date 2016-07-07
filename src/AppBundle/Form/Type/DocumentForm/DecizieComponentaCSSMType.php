<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\PersonType;

class DecizieComponentaCSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('company', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('administrator', TextType::class)
          ->add('presedinte', TextType::class)
          ->add('secretar', TextType::class)
          ->add('membrii', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('medic', TextType::class);
    }

    public function getName()
    {
        return 'decizie_componenta_cssm_type';
    }

}