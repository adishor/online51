<?php

namespace AppBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
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
          ->add('credits', null, array(
              'required' => false,
          ))
          ->end()
          ->with('Profile', array('class' => 'col-md-6'))
          ->add('name')
          ->add('phone', null, array(
              'required' => false,
          ))
          ->add('county', null, array(
              'required' => false,
          ))
          ->add('city', null, array(
              'required' => false,
          ))
          ->add('address', null, array(
              'required' => false,
          ))
          ->end()
          ->with('Company', array('class' => 'col-md-6'))
          ->add('company')
          ->add('uploadImage', 'sonata_admin_image_file', array(
              'required' => false
          ))
          ->add('noEmployees', 'choice', array(
              'choices' => array(
                  User::NO_EMPLOYEES_0_9 => '0-9',
                  User::NO_EMPLOYEES_10_49 => '10-49',
                  User::NO_EMPLOYEES_OVER_50 => 'peste 50'
              ),
              'empty_value' => 'Selectati Nr de angajati',
              'required' => false
          ))
          ->add('cui', null, array(
              'required' => false,
          ))
          ->add('bank', null, array(
              'required' => false,
          ))
          ->add('iban', null, array(
              'required' => false,
          ))
          ->add('noRegistrationORC', null, array(
              'required' => false,
          ))
          ->add('noCertifiedEmpowerment', null, array(
              'required' => false,
          ))
          ->end()
          ->end()
          ->removeGroup('Social', 'User');
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/user/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('getCities', 'getCities');
    }

    public function getFormTheme()
    {
        return array_merge(
          parent::getFormTheme(), array('sonata/admin_image_file_field.html.twig')
        );
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
          ->tab('User')
          ->with('General')
          ->add('email')
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
          ->with('Profile')
          ->add('name')
          ->add('phone')
          ->add('county')
          ->add('city')
          ->add('address')
          ->end()
          ->with('Company')
          ->add('company')
          ->add('uploadImage', 'sonata_admin_image_file', array(
              'template' => 'sonata/show_admin_image_file_field.html.twig'
          ))
          ->add('noEmployees', 'choice', array(
              'choices' => array(
                  User::NO_EMPLOYEES_0_9 => '0-9',
                  User::NO_EMPLOYEES_10_49 => '10-49',
                  User::NO_EMPLOYEES_OVER_50 => 'peste 50'
              )))
          ->add('cui')
          ->add('bank')
          ->add('iban')
          ->add('noRegistrationORC')
          ->add('noCertifiedEmpowerment')
          ->end()
          ->end()
        ;
    }

}