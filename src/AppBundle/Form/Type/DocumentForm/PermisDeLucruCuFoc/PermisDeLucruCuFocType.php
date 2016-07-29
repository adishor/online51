<?php

namespace AppBundle\Form\Type\DocumentForm\PermisDeLucruCuFoc;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class PermisDeLucruCuFocType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime();
        $year = $date->format('Y');
        $years = array(
            $year => $year,
            $year + 1 => $year + 1,
            $year + 2 => $year + 2,
            $year + 3 => $year + 3,
            $year + 4 => $year + 4,
            $year + 5 => $year + 5
        );

        $builder
          ->add('company', TextType::class)
          ->add('personWithWorkPermitForFire', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('helpPersonForWorkWithFire', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('executeWork', TextType::class)
          ->add('useForWork', TextType::class)
          ->add('forWork', TextType::class)
          ->add('startWorkDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_seconds' => FALSE,
              'years' => $years
          ))
          ->add('endWorkDate', DateTimeType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua', 'hour' => 'Ora'
              ),
              'with_seconds' => FALSE,
              'years' => $years
          ))
          ->add('measure1ProtectionRadiusOfMeters', TextType::class)
          ->add('measure1', TextareaType::class)
          ->add('measure2', TextareaType::class)
          ->add('measure3', TextareaType::class)
          ->add('measure5No', TextType::class)
          ->add('measure5Date', DateType::class, array(
              'placeholder' => array(
                  'year' => 'An', 'month' => 'Luna', 'day' => 'Ziua'
              ),
              'years' => $years
          ))
          ->add('measure5ReleasedBy', TextType::class)
          ->add('measure6', TextareaType::class)
          ->add('measure7', TextareaType::class)
          ->add('measure9SurveillanceBy', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('measure11AssuredBy', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
          ->add('measure12SurveillanceBy', CollectionType::class, array(
              'entry_type' => PersonType::class,
          ))
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