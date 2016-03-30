<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SubDomainAdmin extends Admin
{
    public function configureFormFields(FormMapper $form)
    {
        $form->add('name')
            ->add('description', 'sonata_simple_formatter_type', array(
                'format' => 'richhtml'
            ))
            ->add('domain');
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
                ->add('domain');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
            ->add('domain');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
            ->add('description')
            ->add('domain');
    }
}