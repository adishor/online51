<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
          ->add('email', 'text')
          ->add('phone', 'text')
          ->add('subject', 'text')
          ->add('message', 'textarea')
          ->add('save', 'submit')
          ->add('captcha', 'captcha', array(
              'width' => 200,
              'height' => 40,
              'length' => 6,
              'reload' => true,
              'as_url' => true,
          ))
          ->getForm();
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}

