<?php

namespace AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EGD2StocareTratareTransportDeseuriType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];

        $stocareTip = $container->getParameter('tip_stocare');
        $tratareMod = $container->getParameter('mod_tratare');
        $tratareScop = $container->getParameter('scop_tratare');
        $transportMijloc = $container->getParameter('mijloc_transport');
        $transportDestinatie = $container->getParameter('destinatia');

        $builder->add('luna', TextType::class, array(
              'read_only' => TRUE, 'disabled' => TRUE,
          ))
          ->add('sectia', TextType::class)
          ->add('stocareCantitate', NumberType::class)
          ->add('stocareTip', ChoiceType::class, array('choices' => $stocareTip))
          ->add('tratareCantitate', NumberType::class)
          ->add('tratareMod', ChoiceType::class, array('choices' => $tratareMod))
          ->add('tratareScop', ChoiceType::class, array('choices' => $tratareScop))
          ->add('transportMijloc', ChoiceType::class, array('choices' => $transportMijloc))
          ->add('transportDestinatie', ChoiceType::class, array('choices' => $transportDestinatie))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor\EGD2StocareTratareTransportDeseuri',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'EGD2_stocare_tratare_transport_deseuri';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}