<?php

namespace AppBundle\Form\Type\DocumentForm\DeciziePersonalCuAtributiiPSI;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class DeciziePersonalCuAtributiiPSIType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getProfile()->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('administrator', TextType::class)
          ->add('workersAttributions', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('interventionTeam', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
        ;
    }

    public function getName()
    {
        return 'decizie_personal_cu_atributii_psi_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}