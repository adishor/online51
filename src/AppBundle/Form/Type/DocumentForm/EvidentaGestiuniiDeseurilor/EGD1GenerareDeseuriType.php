<?php

namespace AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EGD1GenerareDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('luna', TextType::class, array(
            'read_only' => TRUE, 'disabled' => TRUE,
          ))
          ->add('cantitateDeseuGenerate', NumberType::class)
          ->add('cantitateDeseuValorificata', NumberType::class)
          ->add('cantitateDeseuEliminata', NumberType::class)
          ->add('cantitateDeseuInStoc', NumberType::class, array(
              'read_only' => true
          ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor\EGD1GenerareDeseuri',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'EGD1_generare_deseuri';
    }

}