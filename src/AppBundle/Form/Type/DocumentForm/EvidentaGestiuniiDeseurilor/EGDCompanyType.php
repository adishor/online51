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
//          ->add('startMonth', ChoiceType::class, array(
//              'choices' => array(
//                  EGDCompany::MONTH_JANUARY => 'month.january',
//                  EGDCompany::MONTH_FEBRUARY => 'month.february',
//                  EGDCompany::MONTH_MARCH => 'month.march',
//                  EGDCompany::MONTH_APRIL => 'month.april',
//                  EGDCompany::MONTH_MAY => 'month.may',
//                  EGDCompany::MONTH_JUNE => 'month.june',
//                  EGDCompany::MONTH_JULY => 'month.july',
//                  EGDCompany::MONTH_AUGUST => 'month.august',
//                  EGDCompany::MONTH_SEPTEMBER => 'month.september',
//                  EGDCompany::MONTH_OCTOMBER => 'month.octomber',
//                  EGDCompany::MONTH_NOVEMBER => 'month.november',
//                  EGDCompany::MONTH_DECEMBER => 'month.december'
//              ),
//          ))
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