<?php

namespace AppBundle\Form\Type\DocumentForm\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PersonType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('gender', ChoiceType::class, array(
              'choices' => array(
                  'Dl' => 'Dl',
                  'Dna' => 'Dna'
              ),
              'placeholder' => '',
              'empty_data' => null
          ))
          ->add('name', TextType::class)
          ->add('function', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\Common\Person',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'person_type';
    }

}