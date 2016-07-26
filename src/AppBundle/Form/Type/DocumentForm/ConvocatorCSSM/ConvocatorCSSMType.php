<?php

namespace AppBundle\Form\Type\DocumentForm\ConvocatorCSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;
use AppBundle\Form\Type\DocumentForm\Common\ConvocatorCSSMPunctType;

class ConvocatorCSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime();
        $year = $date->format('Y');

        $builder
          ->add('company', TextType::class)
          ->add('companyCounty', EntityType::class, array(
              'class' => 'AppBundle:ROCounty',
              'choice_label' => 'name'
          ))
          ->add('companyCity', EntityType::class, array(
              'class' => 'AppBundle:ROCity',
              'choice_label' => 'name'
          ))
          ->add('meetingDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_seconds' => FALSE,
              'years' => array(
                  $year => $year,
                  $year + 1 => $year + 1,
                  $year + 2 => $year + 2,
                  $year + 3 => $year + 3,
                  $year + 4 => $year + 4,
                  $year + 5 => $year + 5
              )
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