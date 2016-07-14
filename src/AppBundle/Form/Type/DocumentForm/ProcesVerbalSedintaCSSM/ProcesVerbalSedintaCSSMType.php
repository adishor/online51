<?php

namespace AppBundle\Form\Type\DocumentForm\ProcesVerbalSedintaCSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;
use AppBundle\Form\Type\DocumentForm\Common\ConvocatorCSSMPunctType;

class ProcesVerbalSedintaCSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('room', TextType::class)
          ->add('meetingDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'with_minutes' => FALSE,
              'with_seconds' => FALSE
          ))
          ->add('president', TextType::class)
          ->add('secretary', TextType::class)
          ->add('members', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('doctor', TextType::class)
          ->add('meetingPoints', CollectionType::class, array(
              'entry_type' => ConvocatorCSSMPunctType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
        ;
    }

    public function getName()
    {
        return 'proces_verbal_sedinta_cssm_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}