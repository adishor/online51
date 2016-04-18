<?php

namespace AppBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Application\Sonata\UserBundle\Entity\User;

class UserAdmin extends SonataUserAdmin {

    protected function configureFormFields(FormMapper $formMapper) {
        parent::configureFormFields($formMapper);

        $formMapper
            ->removeGroup('Profile', 'User')
            ->tab('User')
            ->with('General')
            ->add('function', 'choice', array(
                'choices' => array(
                    User::FUNCTION_EXTERN_JOB => 'user.function.extern_job',
                    User::FUNCTION_INTERN_JOB => 'user.function.intern_job',
                    User::FUNCTION_APPOINTED_WORKER => 'user.function.appointed_worker',
                    User::FUNCTION_ADMINISTRATOR => 'user.function.administrator'
                )
            ))
            ->add('creditsTotal', null, array(
                'required' => false,
                'read_only' => true
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
                    User::NO_EMPLOYEES_0_9 => 'user.employees.0_9',
                    User::NO_EMPLOYEES_10_49 => 'user.employees.10_49',
                    User::NO_EMPLOYEES_OVER_50 => 'user.employees.over_50'
                ),
                'empty_value' => 'user_employees_empty',
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

    public function getTemplate($name) {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

    public function configureRoutes(RouteCollection $collection) {
        $collection->add('getCities', 'getCities');
    }

    public function getFormTheme() {
        return array_merge(
                parent::getFormTheme(), array('sonata/fieldType/admin_image_file_field.html.twig')
        );
    }

    protected function configureShowFields(ShowMapper $show) {
        $show
            ->tab('User')
            ->with('General')
            ->add('email')
            ->add('function', 'choice', array(
                'choices' => array(
                    User::FUNCTION_EXTERN_JOB => $this->getTranslator()->trans('user.function.extern_job'),
                    User::FUNCTION_INTERN_JOB => $this->getTranslator()->trans('user.function.intern_job'),
                    User::FUNCTION_APPOINTED_WORKER => $this->getTranslator()->trans('user.function.appointed_worker'),
                    User::FUNCTION_ADMINISTRATOR => $this->getTranslator()->trans('user.function.administrator')
                ),
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
                'template' => 'sonata/fieldType/show_admin_image_file_field.html.twig'
            ))
            ->add('noEmployees', 'choice', array(
                'choices' => array(
                    User::NO_EMPLOYEES_0_9 => $this->getTranslator()->trans('user.employees.0_9'),
                    User::NO_EMPLOYEES_10_49 => $this->getTranslator()->trans('user.employees.10_49'),
                    User::NO_EMPLOYEES_OVER_50 => $this->getTranslator()->trans('user.employees.over_50')
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
