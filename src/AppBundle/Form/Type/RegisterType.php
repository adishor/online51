<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\Type\ProfileType;

class RegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('email', 'text')
          ->add('password', 'repeated', array(
              'type' => 'password',
              'required' => true,
              'invalid_message' => 'assert.password.same',
          ))
          ->add('profile', new ProfileType())
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
            'validation_groups' => array("flow_register_flow_step1", "ChangeInfo")
        ));
    }

    public function getName()
    {
        return 'register';
    }

}