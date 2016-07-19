<?php

namespace AppBundle\Form\Type\DocumentForm\ConvocatorCSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;
use AppBundle\Form\Type\DocumentForm\Common\ConvocatorCSSMPunctType;

class ConvocatorCSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getDemoAccount() ? FALSE : TRUE,
          ))
//          ->add('companyCounty', null, array(
//              'empty_value' => 'Selectati judetul'
//          ))
//          ->add('companyCity')
          ->add('meetingDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_minutes' => FALSE,
              'with_seconds' => FALSE
          ))
          ->add('meetingPoints', CollectionType::class, array(
              'entry_type' => ConvocatorCSSMPunctType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('administrator', CollectionType::class, array(
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
          ))
        ;
    }

    public function getName()
    {
        return 'convocator_cssm_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}