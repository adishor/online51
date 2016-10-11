<?php

namespace AppBundle\Form\Type\DocumentForm\EvidentaGestiuniiDeseurilor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor\EGDCompany;

class EGDCompanyType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentForm\EvidentaGestiuniiDeseurilor\EGDCompany',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'EGD_company';
    }

}