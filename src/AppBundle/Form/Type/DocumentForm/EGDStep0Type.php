<?php

namespace AppBundle\Form\Type\DocumentForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EGDStep0Type extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];

        $operatia = $container->getParameter('operatia');

        $builder->add('operatia', ChoiceType::class, array('choices' => $operatia));
    }

    public function getName()
    {
        return 'egd_step0';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}