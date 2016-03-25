<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CreditsUsageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('user')
                ->add('credit')
                ->add('mentions');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
                ->add('mentions');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('user')
                ->add('credit')
                ->addIdentifier('mentions')
                ->add('createdAt');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
                ->add('credit')
                ->add('mentions')
                ->add('createdAt');
    }
}

