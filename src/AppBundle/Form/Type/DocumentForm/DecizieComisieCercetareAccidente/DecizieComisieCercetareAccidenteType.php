<?php

namespace AppBundle\Form\Type\DocumentForm\DecizieComisieCercetareAccidente;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class DecizieComisieCercetareAccidenteType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $date = new \DateTime();
        $year = $date->format('Y');
        $years = array(
            $year => $year,
            $year + 1 => $year + 1,
            $year + 2 => $year + 2,
            $year + 3 => $year + 3,
            $year + 4 => $year + 4,
            $year + 5 => $year + 5
        );

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getProfile()->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('undersigned', CollectionType::class, array(
              'entry_type' => PersonType::class
          ))
          ->add('accidentDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_seconds' => FALSE,
              'years' => $years
          ))
          ->add('accidentDescription', TextType::class)
          ->add('members', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('startingActivity', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'years' => $years
          ))
          ->add('endingActivity', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'years' => $years
          ))
          ->add('administrator', CollectionType::class, array(
              'entry_type' => PersonType::class
        ));
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