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
        $builder->add('name', null, array(
                    'required' => true,
                ))
                ->add('function', 'choice', array(
                    'required' => true,
                    'choices' => array(
                        User::FUNCTION_EXTERN_JOB => 'Serviciu extern',
                        User::FUNCTION_INTERN_JOB => 'Serviciu intern',
                        User::FUNCTION_APPOINTED_WORKER => 'Lucrator desemnat',
                        User::FUNCTION_ADMINISTRATOR => 'Administrator'
                    )
                ))
                ->add('email', 'text')
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'required' => true
                ))
                ->add('company')
                ->add('cui')
                ->add('noRegistrationORC')
                ->add('noEmployees', 'choice', array(
                    'required' => true,
                    'choices' => array(
                        User::NO_EMPLOYEES_0_9 => '0-9',
                        User::NO_EMPLOYEES_10_49 => '10-49',
                        User::NO_EMPLOYEES_OVER_50 => 'peste 50'
                    ),
                    'empty_value' => 'Selectati Nr de angajati'
                ))
                ->add('noCertifiedEmpowerment')
                ->add('bank')
                ->add('iban')
                ->add('phone')
                ->add('county', null, array(
                    'empty_value' => 'Selectati judetul'
                ))
                ->add('city', null, array(
                    'required' => false
                ))
                ->add('address')
                ->add('uploadImage', 'file')
                ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
            'validation_groups' => array('CustomRegistration')
        ));
    }

    public function getName()
    {
        return 'register';
    }
}

