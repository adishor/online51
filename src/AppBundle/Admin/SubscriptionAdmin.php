<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SubscriptionAdmin extends Admin
{
    public function configureFormFields(FormMapper $form)
    {
        $form->add('name')
            ->add('intro', 'sonata_simple_formatter_type', array(
                'format' => 'richhtml',
                'required' => false
            ))
            ->add('description', 'sonata_simple_formatter_type', array(
                'format' => 'richhtml',
                'required' => false
            ))
            ->add('price')
            ->add('credit')
            ->add('valability')
            ->add('domainAmount')
            ->add('domains', 'sonata_type_model', array(
                'expanded' => false,
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ));
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
                ->add('domains');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
            ->add('price')
            ->add('credit')
            ->add('valability')
            ->add('domainAmount')
            ->add('domains');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
            ->add('intro')
            ->add('description')
            ->add('price')
            ->add('credit')
            ->add('valability')
            ->add('domainAmount')
            ->add('domains');
    }
}