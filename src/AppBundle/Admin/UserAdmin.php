<?php

namespace AppBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends SonataUserAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->removeGroup('Profile', 'User')
            ->tab('User')
                ->with('General')
                    ->add('function')
                    ->add('credits')
                ->end()
                ->with('Profile', array('class' => 'col-md-6'))
                    ->add('name')
                    ->add('phone')
                    ->add('county')
                    ->add('city')
                    ->add('address')
                ->end()
                ->with('Company', array('class' => 'col-md-6'))
                    ->add('company')
                    ->add('logo')
                    ->add('noEmployees')
                    ->add('cui')
                    ->add('bank')
                    ->add('iban')
                    ->add('noRegistrationORC')
                    ->add('noCertifiedEmpowerment')
                ->end()
            ->end()
            ->removeGroup('Social', 'User');
    }
}

