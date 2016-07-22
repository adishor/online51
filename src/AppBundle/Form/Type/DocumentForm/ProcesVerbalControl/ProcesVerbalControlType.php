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
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getProfile()->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('controlDate', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              )
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
          ->add('administrator', TextType::class)
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