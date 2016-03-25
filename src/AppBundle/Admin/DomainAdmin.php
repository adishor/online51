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
            ->add('description')
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
            ->add('subdomains')
            ->add('subscriptions');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
            ->add('baseline')
            ->add('description')
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
}