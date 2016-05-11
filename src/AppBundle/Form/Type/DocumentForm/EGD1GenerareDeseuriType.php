<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EGD1GenerareDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('luna', TextType::class, array(
            'read_only' => TRUE
          ))
          ->add('cantitateDeseuGenerate', IntegerType::class)
          ->add('cantitateDeseuValorificata', IntegerType::class)
          ->add('cantitateDeseuEliminata', IntegerType::class)
          ->add('cantitateDeseuInStoc', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EGD1GenerareDeseuri'
        ));
    }

    public function getName()
    {
        return 'EGD1_generare_deseuri';
    }

}