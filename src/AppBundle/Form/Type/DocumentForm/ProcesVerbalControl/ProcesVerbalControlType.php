<?php

namespace AppBundle\Form\Type\DocumentForm\ProcesVerbalControl;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;
use AppBundle\Form\Type\DocumentForm\ProcesVerbalControl\ProcesVerbalControlQuestionType;

class ProcesVerbalControlType extends AbstractType
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
          ->add('controlDate', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'years' => $years
          ))
          ->add('controlBy', TextType::class)
          ->add('participants', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('organizationalQuestions', CollectionType::class, array(
              'entry_type' => ProcesVerbalControlQuestionType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('findings', TextareaType::class)
          ->add('proposedMeasures', TextareaType::class)
          ->add('administrator', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
        ;
    }

    public function getName()
    {
        return 'proces_verbal_control_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}