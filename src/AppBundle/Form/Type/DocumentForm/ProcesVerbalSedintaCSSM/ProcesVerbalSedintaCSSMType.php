<?php

namespace AppBundle\Form\Type\DocumentForm\ProcesVerbalSedintaCSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;
use AppBundle\Form\Type\DocumentForm\Common\ConvocatorCSSMPunctType;

class ProcesVerbalSedintaCSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
          ->add('company', TextType::class)
          ->add('companyCity', TextType::class)
          ->add('room', TextType::class)
          ->add('meetingDate', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'years' => $years
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
          ))
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