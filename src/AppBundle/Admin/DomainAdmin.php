<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DomainAdmin extends Admin
{
    public function configureFormFields(FormMapper $form)
    {
        $form->add('name')
            ->add('baseline', null, array(
                'required' => false
            ))
            ->add('description', 'sonata_simple_formatter_type', array(
                'format' => 'richhtml',
                'required' => false
            ))
            ->add('dedicated')
            ->add('subdomains', 'sonata_type_model', array(
                'expanded' => false,
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ))
            ->add('subscriptions', 'sonata_type_model', array(
                'expanded' => false,
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ));
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
                ->add('subdomains')
                ->add('subscriptions');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
            ->add('dedicated')
            ->add('subdomains')
            ->add('subscriptions');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
            ->add('baseline')
            ->add('description')
            ->add('dedicated')
            ->add('subdomains')
            ->add('subscriptions');
    }

    public function prePersist($object)
    {
        $this->preUpdate($object);
    }

    public function preUpdate($object)
    {
        $object->setSubdomains($object->getSubdomains());
    }

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('sonata/admin_orm_one_to_many_field.html.twig')
        );
    }
}