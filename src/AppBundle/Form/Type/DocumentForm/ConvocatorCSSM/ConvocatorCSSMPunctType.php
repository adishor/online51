<?php

namespace AppBundle\Form\Type\DocumentForm\ConvocatorCSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ConvocatorCSSMPunctType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('punctOrdineZi', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\ConvocatorCSSM\ConvocatorCSSMPunct',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'convocator_cssm_type';
    }

}