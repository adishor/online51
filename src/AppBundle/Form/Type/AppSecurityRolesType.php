<?php

namespace AppBundle\Form\Type;

use Sonata\UserBundle\Form\Type\SecurityRolesType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppSecurityRolesType extends SecurityRolesType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'
            ),
            'read_only_choices' => array(),
            'data_class' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_sonata_security_roles';
    }

}