<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class DomainAdmin extends Admin
{

    public function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        //get all subdomains associated to domains
        $choices = [];
        $subdomains = [];
        foreach ($this->getSubject()->getSubdomains() as $subdomain) {
            if (!$subdomain->getDeleted()) {
                $subdomains[$subdomain->getId()] = $subdomain;
            }
        }
        $choices[$this->getSubject()->getName()] = $subdomains;

        //get all subdomains that are not associated
        $noDomainSubdomains = $this->modelManager
          ->getEntityManager('AppBundle:SubDomain')
          ->createQueryBuilder()
          ->select('s')
          ->from('AppBundle:SubDomain', 's')
          ->where('s.domain is NULL')
          ->andWhere('s.deleted = 0')
          ->getQuery()
          ->getResult();
        $choices['No Domain'] = $noDomainSubdomains;

        $subdomainsOptions = array(
            'expanded' => false,
            'multiple' => true,
            'by_reference' => false,
            'required' => false,
            'class' => 'AppBundle:SubDomain',
            'choices' => $choices,
            'disabled' => $disabled
        );

        $querySubscription = $this->modelManager
          ->getEntityManager('AppBundle:Subscription')
          ->createQueryBuilder()
          ->select('s')
          ->from('AppBundle:Subscription', 's')
          ->where('s.deleted = 0');

        $subscriptionsOptions = array(
            'query' => $querySubscription,
            'expanded' => false,
            'multiple' => true,
            'by_reference' => false,
            'required' => false,
            'disabled' => $disabled
        );

        if ($this->getSubject()->getDeleted()) {
            $subscriptionsOptions['btn_add'] = FALSE;
        }

        $form->add('name', null, array(
              'disabled' => $disabled
          ))
          ->add('baseline', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('description', 'sonata_simple_formatter_type', array(
              'format' => 'richhtml',
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('dedicated', null, array(
              'disabled' => $disabled
          ))
          ->add('subdomains', 'entity', $subdomainsOptions)
          ->add('subscriptions', 'sonata_type_model', $subscriptionsOptions)
          ->add('demoDomain', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('demoCreditValue', null, array(
              'required' => false,
              'disabled' => $disabled
        ));
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
          ->add('subdomains')
          ->add('subscriptions')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('dedicated')
          ->add('subdomains')
          ->add('subscriptions')
          ->add('deleted');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('baseline')
          ->add('description', 'html')
          ->add('dedicated')
          ->add('subdomains')
          ->add('subscriptions')
          ->add('demoDomain')
          ->add('demoCreditValue')
          ->add('deleted')
          ->add('deletedAt');
    }

    public function prePersist($object)
    {
        $this->preUpdate($object);
    }

    public function preUpdate($object)
    {
        if ($object->getId()) {

            $params = $this->getRequest()->request->get($this->getUniqid());

            $subdomains = $this->getModelManager()
              ->getEntityManager('AppBundle:SubDomain')
              ->getRepository('AppBundle:SubDomain')
              ->findBy(array('domain' => $object->getId()));

            foreach ($subdomains as $subdomain) {
                if (!in_array($subdomain->getId(), $params['subdomains'])) {
                    $subdomain->setDomain(NULL);
                    $em = $this->configurationPool->getContainer()->get('Doctrine')->getManager();
                    $em->persist($subdomain);
                    $em->flush();
                }
            }
        }
    }

    public function getFormTheme()
    {
        return array_merge(
          parent::getFormTheme(), array('sonata/admin_orm_one_to_many_field.html.twig')
        );
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

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

}