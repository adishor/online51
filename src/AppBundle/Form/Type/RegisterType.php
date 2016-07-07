<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\Sonata\UserBundle\Entity\User;

class RegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name')
          ->add('function', 'choice', array(
              'required' => true,
              'choices' => array(
                  User::FUNCTION_EXTERN_JOB => 'user.function.extern_job',
                  User::FUNCTION_INTERN_JOB => 'user.function.intern_job',
                  User::FUNCTION_APPOINTED_WORKER => 'user.function.appointed_worker',
                  User::FUNCTION_ADMINISTRATOR => 'user.function.administrator'
              )
          ))
          ->add('email', 'text')
          ->add('password', 'repeated', array(
              'type' => 'password',
              'required' => true,
              'invalid_message' => 'assert.password.same',
          ))
          ->add('company')
          ->add('cui')
          ->add('noRegistrationORC')
          ->add('noEmployees', 'choice', array(
              'choices' => array(
                  User::NO_EMPLOYEES_0_9 => 'user.employees.0_9',
                  User::NO_EMPLOYEES_10_49 => 'user.employees.10_49',
                  User::NO_EMPLOYEES_OVER_50 => 'user.employees.over_50'
              ),
              'empty_value' => 'user.employees.select_no'
          ))
          ->add('noCertifiedEmpowerment')
          ->add('bank')
          ->add('iban')
          ->add('phone')
          ->add('county', null, array(
              'empty_value' => 'Selectati judetul'
          ))
          ->add('city')
          ->add('address')
          ->add('image', 'sonata_media_type', array(
              'provider' => 'sonata.media.provider.image',
              'context' => 'default',
              'required' => false,
          ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'register';
    }

}