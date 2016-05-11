<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EGD3ValorificareDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('luna', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('cantitateDeseuValorificata', IntegerType::class)
          ->add('operatiaDeValorificare', IntegerType::class)
          ->add('agentEconomicValorificare', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EGD3ValorificareDeseuri'
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