<?php

namespace AppBundle\Form\Type\DocumentForm\PermisDeLucruCuFoc;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PermisDeLucruCuFocType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getProfile()->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('personWithWorkPermitForFire', TextType::class)
          ->add('helpPersonForWorkWithFire', TextType::class)
          ->add('executeWork', TextType::class)
          ->add('useForWork', TextType::class)
          ->add('forWork', TextType::class)
          ->add('startWorkDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_minutes' => FALSE,
              'with_seconds' => FALSE
          ))
          ->add('endWorkDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_minutes' => FALSE,
              'with_seconds' => FALSE
          ))
          ->add('measure1ProtectionRadiusOfMeters', TextType::class)
          ->add('measure1', TextareaType::class)
          ->add('measure2', TextareaType::class)
          ->add('measure3', TextareaType::class)
          ->add('measure5No', TextType::class)
          ->add('measure5Date', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              )
          ))
          ->add('measure5ReleasedBy', TextType::class)
          ->add('measure6', TextareaType::class)
          ->add('measure7', TextareaType::class)
          ->add('measure9SurveillanceBy', TextType::class)
          ->add('measure11AssuredBy', TextType::class)
          ->add('measure12SurveillanceBy', TextType::class)
          ->add('measure13AnnoucementAt', TextType::class)
          ->add('measure13AnnoucementTrought', TextType::class)
          ->add('measure14', TextareaType::class)
          ->add('measure15Issuer', TextType::class)
          ->add('measure15ChiefSector', TextType::class)
          ->add('measure15Workers', TextType::class)
          ->add('measure15Emergency', TextType::class)
        ;
    }

    public function getName()
    {
        return 'permis_de_lucru_cu_foc_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}