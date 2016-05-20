<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EGD4EliminareDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $operatieEliminare = $container->getParameter('operatia_eliminare_deseu');

        $builder->add('luna', TextType::class, array(
              'read_only' => TRUE, 'disabled' => TRUE,
          ))
          ->add('cantitateDeseuEliminata', NumberType::class)
          ->add('operatiaDeEliminare', ChoiceType::class, array('choices' => $operatieEliminare))
          ->add('agentEconomicEliminare', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EGD4EliminareDeseuri',
            'cascade_validation' => true,
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