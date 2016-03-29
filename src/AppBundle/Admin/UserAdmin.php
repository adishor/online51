<?php

namespace AppBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Application\Sonata\UserBundle\Entity\User;

class UserAdmin extends SonataUserAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->removeGroup('Profile', 'User')
            ->tab('User')
                ->with('General')
                    ->add('function', 'choice', array(
                        'choices' => array(
                            User::FUNCTION_EXTERN_JOB => 'Serviciu extern',
                            User::FUNCTION_INTERN_JOB => 'Serviciu intern',
                            User::FUNCTION_APPOINTED_WORKER => 'Lucrator desemnat',
                            User::FUNCTION_ADMINISTRATOR => 'Administrator'
                        )
                    ))
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
                    ->add('logoImage', 'sonata_admin_image_file', array(
                        'required' => false
                    ))
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

    public function getTemplate($name)
    {
        if ( $name == "edit" )
        {
            return 'sonata/user/base_edit.html.twig' ;
        }
        return parent::getTemplate($name);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('getCities', $this->getRouterIdParameter().'/getCities');
    }

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('sonata/admin_image_file_field.html.twig')
        );
    }
}

