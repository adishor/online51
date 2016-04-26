<?php

namespace AppBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Application\Sonata\UserBundle\Entity\User;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class UserAdmin extends SonataUserAdmin
{

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || is_null($this->getSubject()->getId())) ? 'AdminRegistration' : 'AdminProfile';

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $formMapper
            ->removeGroup('Profile', 'User')
            ->tab('User')
            ->with('General', array('class' => 'col-md-6'))
            ->add('username', null, array(
                'disabled' => $disabled
            ))
            ->add('email', null, array(
                'disabled' => $disabled
            ))
            ->add('plainPassword', 'text', array(
                'disabled' => $disabled
            ))
            ->add('name', null, array(
                'disabled' => $disabled
            ))
            ->add('phone', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('county', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('city', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('address', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('creditsTotal', null, array(
                'required' => false,
                'read_only' => true,
                'disabled' => $disabled
            ))
            ->end()
            ->with('Company', array('class' => 'col-md-6'))
            ->add('function', 'choice', array(
                'choices' => array(
                    User::FUNCTION_EXTERN_JOB => 'user.function.extern_job',
                    User::FUNCTION_INTERN_JOB => 'user.function.intern_job',
                    User::FUNCTION_APPOINTED_WORKER => 'user.function.appointed_worker',
                    User::FUNCTION_ADMINISTRATOR => 'user.function.administrator'
                ),
                'disabled' => $disabled
            ))
            ->add('company', null, array(
                'disabled' => $disabled
            ))
//            ->add('uploadImage', 'sonata_admin_image_file', array(
//                'required' => false,
//                'disabled' => $disabled
//            ))
            ->add('noEmployees', 'choice', array(
                'choices' => array(
                    User::NO_EMPLOYEES_0_9 => 'user.employees.0_9',
                    User::NO_EMPLOYEES_10_49 => 'user.employees.10_49',
                    User::NO_EMPLOYEES_OVER_50 => 'user.employees.over_50'
                ),
                'empty_value' => 'user_employees_empty',
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('cui', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('bank', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('iban', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('noRegistrationORC', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->add('noCertifiedEmpowerment', null, array(
                'required' => false,
                'disabled' => $disabled
            ))
            ->end()
            ->end()
            ->removeGroup('Social', 'User');
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
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
                parent::getFormTheme(), array('sonata/fieldType/admin_image_file_field.html.twig')
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
                    ->add('deleted')
                    ->add('deletedAt')
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

    protected function configureDatagridFilters(DatagridMapper $filterMapper) {
        parent::configureDatagridFilters($filterMapper);

        $filterMapper->add('deleted');
    }

    protected function configureListFields(\Sonata\AdminBundle\Datagrid\ListMapper $listMapper) {
        parent::configureListFields($listMapper);

        $listMapper->add('deleted');
    }

    public function getFilterParameters()
    {
        $parameters = parent::getFilterParameters();

        if (!array_key_exists("deleted", $parameters)) {
            $parameters['deleted'] = array (
                'type' => EqualType::TYPE_IS_EQUAL,
                'value' => BooleanType::TYPE_NO
            );
        }

        return $parameters;
    }

    public function prePersist($object)
    {
        $object->setDeleted(false);
    }
}