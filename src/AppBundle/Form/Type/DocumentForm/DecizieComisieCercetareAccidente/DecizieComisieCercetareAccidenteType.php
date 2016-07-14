<?php

namespace AppBundle\Form\Type\DocumentForm\DecizieComisieCercetareAccidente;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class DecizieComisieCercetareAccidenteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('undersigned', CollectionType::class, array(
              'entry_type' => PersonType::class
          ))
          ->add('accidentDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_minutes' => FALSE,
              'with_seconds' => FALSE
          ))
          ->add('accidentDescription', TextType::class)
          ->add('members', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('startingActivity', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'with_minutes' => FALSE,
              'with_seconds' => FALSE
          ))
          ->add('administrator', TextType::class);
    }

    public function getName()
    {
        return 'decizie_comisie_cercetare_accidente_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}