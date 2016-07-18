<?php

namespace AppBundle\Form\Type\DocumentForm\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ConvocatorCSSMPunctType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('meetingPoint', TextareaType::class)
          ->add('meetingPointSummary', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\Common\ConvocatorCSSMPunct',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'convocator_cssm_punct_type';
    }

}