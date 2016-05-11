<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EGD4EliminareDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('luna', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('cantitateDeseuEliminata', IntegerType::class)
          ->add('operatiaDeEliminare', IntegerType::class)
          ->add('agentEconomicEliminare', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EGD4EliminareDeseuri'
        ));
    }

    public function getName()
    {
        return 'EGD4_eliminare_deseuri';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}