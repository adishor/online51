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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;

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
          ->add('tipDeseu', TextType::class, array(
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
          ->add('save1', SubmitType::class)
          ->add('save1Clicked', HiddenType::class, array('mapped' => false))
          ->add('save2', SubmitType::class)
          ->add('save2Clicked', HiddenType::class, array('mapped' => false))
          ->add('save3', SubmitType::class)
          ->add('save3Clicked', HiddenType::class, array('mapped' => false))
          ->add('save4', SubmitType::class)
          ->add('save4Clicked', HiddenType::class, array('mapped' => false))
          ->add('save5', SubmitType::class)
          ->add('save5Clicked', HiddenType::class, array('mapped' => false))
          ->add('generateDocument', SubmitType::class)
          ->add('generateDocumentClicked', HiddenType::class, array('mapped' => false))
//          ->add('save', SubmitType::class)
          ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor',
            'validation_groups' => function(FormInterface $form) {
                if ($form->get('save1Clicked')->getData() === 'true') {
                    return array('button1');
                }
                if ($form->get('save2Clicked')->getData() === 'true') {
                    return array('button1', 'button2');
                }
                if ($form->get('save3Clicked')->getData() === 'true') {
                    return array('button1', 'button2', 'button3');
                }
                if ($form->get('save4Clicked')->getData() === 'true') {
                    return array('button1', 'button2', 'button3', 'button4');
                }
                if ($form->get('save5Clicked')->getData() === 'true') {
                    return array('button1', 'button2', 'button3', 'button4', 'button5');
                }
                if ($form->get('generateDocumentClicked')->getData() === 'true') {
                    return array('button1', 'button2', 'button3', 'button4', 'button5', 'generateDocument');
                }
                return array('Default');
            },
              'cascade_validation' => true,
          ));
      }

      public function getName()
      {
          return 'evidenta_gestiunii_deseurilor';
      }

  }