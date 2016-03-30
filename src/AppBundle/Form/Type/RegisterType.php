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
                    'label' => 'Nume si prenume'
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
                ->add('email', 'email')
                ->add('password')
                ->add('company')
                ->add('cui')
                ->add('noRegistrationORC')
                ->add('noEmployees')
                ->add('bank')
                ->add('iban')
                ->add('phone')
                ->add('county')
                ->add('city')
                ->add('logo', 'file')
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

