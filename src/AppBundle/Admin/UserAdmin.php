<?php

namespace AppBundle\Admin;

use AppBundle\Helper\UserHelper;
use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use AppBundle\Entity\Profile;
use Application\Sonata\UserBundle\Entity\User;

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
        // define group zoning
        $formMapper
          ->tab('User')
          ->with('General', array('class' => 'col-md-6'))->end()
          ->with('Company', array('class' => 'col-md-6'))->end()
          ->end()
        ;

        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $formMapper
          ->tab('User')
          ->with('General')
          ->add('username', null, array(
              'disabled' => $disabled
          ))
          ->add('email', null, array(
              'disabled' => $disabled
          ))
          ->add('locked', null, array('required' => false))
          ->add('expired', null, array('required' => false))
          ->add('enabled', null, array('required' => false))
          ->add('credentialsExpired', null, array('required' => false))
          ->add('groups', 'sonata_type_model', array(
              'required' => false,
              'expanded' => true,
              'multiple' => true,
          ))
          ->add('realRoles', 'app_sonata_security_roles', array(
              'label' => 'form.label_roles',
              'expanded' => true,
              'multiple' => true,
              'required' => false,
          ))
          ->end()
          ->with('Company')
          ->add('profile', 'sonata_type_admin', array(), array('edit' => 'inline'))
          ->add('creditsTotal', null, array(
              'required' => false,
              'read_only' => true,
              'disabled' => $disabled
          ))
          ->end()
          ->end()
        ;
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

    protected function configureShowFields(ShowMapper $show)
    {
        $show
          ->tab('User')
          ->with('General')
          ->add('email')
          ->end()
          ->with('Profile')
          ->add('profile.name')
          ->add('profile.function', 'choice', array(
              'choices' => array(
                  Profile::FUNCTION_EXTERN_JOB => $this->getTranslator()->trans('user.function.extern_job'),
                  Profile::FUNCTION_INTERN_JOB => $this->getTranslator()->trans('user.function.intern_job'),
                  Profile::FUNCTION_APPOINTED_WORKER => $this->getTranslator()->trans('user.function.appointed_worker'),
                  Profile::FUNCTION_ADMINISTRATOR => $this->getTranslator()->trans('user.function.administrator')
            )))
          ->add('profile.company')
          ->add('profile.image', 'sonata_media_type', array(
              'provider' => 'sonata.media.provider.image',
              'context' => 'default',
              'template' => 'sonata/user_base_show_field.html.twig',
          ))
          ->add('profile.phone')
          ->add('profile.county')
          ->add('profile.city')
          ->add('profile.address')
          ->add('profile.noEmployees', 'choice', array(
              'choices' => array(
                  Profile::NO_EMPLOYEES_0_9 => $this->getTranslator()->trans('user.employees.0_9'),
                  Profile::NO_EMPLOYEES_10_49 => $this->getTranslator()->trans('user.employees.10_49'),
                  Profile::NO_EMPLOYEES_OVER_50 => $this->getTranslator()->trans('user.employees.over_50')
            )))
          ->add('profile.cui')
          ->add('profile.bank')
          ->add('profile.iban')
          ->add('profile.noRegistrationORC')
          ->add('profile.noCertifiedEmpowerment')
          ->add('credits')
          ->add('deleted')
          ->add('deletedAt')
          ->end()
          ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        parent::configureDatagridFilters($filterMapper);

        $filterMapper->add('deleted', null, array(), null, array('choices_as_values' => true))
          ->remove('locked')
          ->remove('id');
    }

    protected function configureListFields(\Sonata\AdminBundle\Datagrid\ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('deleted')
          ->remove('impersonating');
    }

    public function getFilterParameters()
    {
        $parameters = parent::getFilterParameters();

        if (!array_key_exists("deleted", $parameters)) {
            $parameters['deleted'] = array(
                'type' => EqualType::TYPE_IS_EQUAL,
                'value' => BooleanType::TYPE_NO
            );
        }

        return $parameters;
    }

    public function prePersist($object)
    {
        if (!$object instanceof User) {
            throw new \Exception("Wrong type of instance given.");
        }

        $object->setUsername($object->getEmail());

        $generatedPassword = UserHelper::generateRandomPassword();

        $object->setPlainPassword($generatedPassword);
        $object->addRole(User::ROLE_DEFAULT);
        $object->setEnabled(true);

        $this->configurationPool->getContainer()->get('app.mailer')->sendAdminCreatedAccountMessage($object, $generatedPassword);
    }

    public function preRemove($object)
    {
        if (!$object->getDeleted()) {
            $object->setLocked(true);
            $object->setUsername($object->getUsername() . '_deleted_' . $object->getCreatedAt()->format('Y-m-d H:i:s'));
            $object->setEmail($object->getEmail() . '_deleted_' . $object->getCreatedAt()->format('Y-m-d H:i:s'));
            $this->getModelManager()->getEntityManager($this->getClass())->flush();
        }
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        if (!$this->getConfigurationPool()->getContainer()->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $query->andWhere('NOT ' . $query->getRootAliases()[0] . '.roles LIKE :role')
              ->setParameter('role', '%"ROLE_SUPER_ADMIN"%');
        }

        return $query;
    }

    public function preBatchAction($actionName, \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $query, array &$idx, $allElements)
    {
        if (empty($idx) && $allElements && $actionName === 'delete') {
            $idx = array();
            $query->select('DISTINCT ' . $query->getRootAlias());
            foreach ($query->getQuery()->iterate() as $pos => $object) {
                $idx[] = $object[0]->getId();
            }
        }
        foreach ($idx as $id) {
            $this->preRemove($this->getModelManager()->getEntityManager($this->getClass())->getRepository('ApplicationSonataUserBundle:User')->find($id));
        }

        parent::preBatchAction($actionName, $query, $idx, $allElements);
    }

}