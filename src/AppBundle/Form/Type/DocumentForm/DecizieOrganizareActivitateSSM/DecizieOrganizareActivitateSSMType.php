<?php

namespace AppBundle\Form\Type\DocumentForm\DecizieOrganizareActivitateSSM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\DocumentForm\Common\PersonType;

class DecizieOrganizareActivitateSSMType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
          ->add('company', TextType::class, array(
              'read_only' => $user->getDemoAccount() ? FALSE : TRUE,
          ))
          ->add('administrator', TextType::class)
          ->add('membersForPreventionProtectionService', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
          ))
          ->add('leaders', CollectionType::class, array(
              'entry_type' => PersonType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'prototype' => true
        ));
    }

    public function getName()
    {
        return 'decizie_organizare_activitate_ssm_type';
    }

    public function getParent()
    {
        return 'container_aware';
    }

}