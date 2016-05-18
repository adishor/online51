<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\EGD1GenerareDeseuriType;
use AppBundle\Form\Type\DocumentForm\EGD2StocareTratareTransportDeseuriType;
use AppBundle\Form\Type\DocumentForm\EGD3ValorificareDeseuriType;
use AppBundle\Form\Type\DocumentForm\EGD4EliminareDeseuriType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvidentaGestiuniiDeseurilorType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('agentEconomic', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('an', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('tipDeseu', TextareaType::class, array(
              'read_only' => TRUE
          ))
          ->add('tipDeseuCod', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('stareFizica', TextType::class)
          ->add('unitateMasura', TextType::class)
          ->add('EGD1GenerareDeseuri', CollectionType::class, array(
              'entry_type' => EGD1GenerareDeseuriType::class,
          ))
          ->add('EGD2StocareTratareTransportDeseuri', CollectionType::class, array(
              'entry_type' => EGD2StocareTratareTransportDeseuriType::class,
          ))
          ->add('EGD3ValorificareDeseuri', CollectionType::class, array(
              'entry_type' => EGD3ValorificareDeseuriType::class,
          ))
          ->add('EGD4EliminareDeseuri', CollectionType::class, array(
              'entry_type' => EGD4EliminareDeseuriType::class,
          ))
          ->add('save1', SubmitType::class, array('validation_groups' => array('button1')))
          ->add('save2', SubmitType::class, array('validation_groups' => array('button1', 'button2')))
          ->add('save3', SubmitType::class, array('validation_groups' => array('button1', 'button2', 'button3')))
          ->add('save4', SubmitType::class, array('validation_groups' => array('button1', 'button2', 'button3', 'button4')))
          ->add('save5', SubmitType::class, array('validation_groups' => array('button1', 'button2', 'button3', 'button5')))
          ->add('generateDocument', SubmitType::class, array('validation_groups' => array('button1', 'button2', 'button3', 'button4', 'button5', 'generateDocument')))
          ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor',
            'cascade_validation' => true,
        ));
      }

      public function getName()
      {
          return 'evidenta_gestiunii_deseurilor';
      }

  }