<?php

namespace AppBundle\Form\Type\DocumentForm\DecizieComponentaCSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class DecizieComponentaCSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('company', TextType::class)
          ->add('administrator', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('president', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('secretary', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('members', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('doctor', CollectionType::class, array(
              'entry_type' => PersonType::class,
        ));
    }

    public function getName()
    {
        return 'decizie_componenta_cssm_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}