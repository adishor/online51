<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EGD3ValorificareDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $operatieValorificare = $container->getParameter('operatia_valorificare_deseu');


        $builder->add('luna', TextType::class, array(
              'read_only' => TRUE, 'disabled' => TRUE,
          ))
          ->add('cantitateDeseuValorificata', NumberType::class)
          ->add('operatiaDeValorificare', ChoiceType::class, array('choices' => $operatieValorificare))
          ->add('agentEconomicValorificare', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EGD3ValorificareDeseuri',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'EGD3_valorificare_deseuri';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}