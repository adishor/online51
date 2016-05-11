<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
USE Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
              'read_only' => TRUE
          ))
          ->add('sectia', IntegerType::class)
          ->add('stocareCantitate', IntegerType::class)
          ->add('stocareTip', IntegerType::class)
          ->add('tratareCantitate', IntegerType::class)
          ->add('tratareMod', IntegerType::class)
          ->add('tratareScop', IntegerType::class)
          ->add('transportMijloc', IntegerType::class)
          ->add('transportDestinatie', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EGD2StocareTratareTransportDeseuri'
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