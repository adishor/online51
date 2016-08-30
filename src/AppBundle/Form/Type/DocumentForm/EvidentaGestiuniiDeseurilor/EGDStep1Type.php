<?php

namespace AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor\EGDCompanyType;

class EGDStep1Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];

        $stocareTip = $container->getParameter('tip_stocare');
        $tratareMod = $container->getParameter('mod_tratare');
        $stareFizica = $container->getParameter('stare_fizica');
        $unitateMasura = $container->getParameter('unitate_masura');
        $transportMijloc = $container->getParameter('mijloc_transport');
        $transportDestinatie = $container->getParameter('destinatia');
        $operatieValorificare = $container->getParameter('operatia_valorificare_deseu');
        $operatieEliminare = $container->getParameter('operatia_eliminare_deseu');

        $builder
          ->add('agentEconomic', TextType::class)
          ->add('an', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('tipDeseu', TextareaType::class, array(
              'read_only' => TRUE
          ))
          ->add('tipDeseuCod', TextType::class, array(
              'read_only' => TRUE
          ))
          ->add('stareFizica', ChoiceType::class, array('choices' => $stareFizica))
          ->add('unitateMasura', ChoiceType::class, array('choices' => $unitateMasura))
          ->add('stocareTip', ChoiceType::class, array('choices' => $stocareTip))
          ->add('tratareMod', ChoiceType::class, array(
              'choices' => $tratareMod,
              'placeholder' => '',
              'empty_data' => null
          ))
          ->add('transportMijloc', ChoiceType::class, array('choices' => $transportMijloc))
          ->add('transportDestinatie', ChoiceType::class, array('choices' => $transportDestinatie))
          ->add('operatiaDeValorificare', ChoiceType::class, array('choices' => $operatieValorificare))
          ->add('operatiaDeEliminare', ChoiceType::class, array('choices' => $operatieEliminare))
          ->add('EGDCompany', CollectionType::class, array(
              'entry_type' => EGDCompanyType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
        ));
    }

    public function getName()
    {
        return 'egd_step1';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}