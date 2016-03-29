<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('function', 'choice', array(
                    'required' => true,
                    'choices' => array(
                        User::FUNCTION_EXTERN_JOB => 'Serviciu extern',
                        User::FUNCTION_INTERN_JOB => 'Serviciu intern',
                        User::FUNCTION_APPOINTED_WORKER => 'Lucrator desemnat',
                        User::FUNCTION_ADMINISTRATOR => 'Administrator'
                    )
                ))
                ->add('email', 'email')
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options'  => array('label' => 'Parola'),
                    'second_options' => array('label' => 'Confirmare parola')
                ))
                ->add('company')
                ->add('cui')
                ->add('noRegistrationORC')
                ->add('noEmployees')
                ->add('bank')
                ->add('iban')
                ->add('phone')
                ->add('county')
                ->add('city')
                ->add('logo')
                ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'register';
    }
}

