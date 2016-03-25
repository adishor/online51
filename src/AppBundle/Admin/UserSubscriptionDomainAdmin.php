<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserSubscriptionDomainAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('user')
                ->add('domain')
                ->add('userSubscription')
                ->add('startDate')
                ->add('endingDate');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
                ->add('domain');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('user')
                ->add('domain')
                ->add('userSubscription')
                ->add('startDate')
                ->add('endingDate');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
                ->add('domain')
                ->add('userSubscription')
                ->add('startDate')
                ->add('endingDate');
    }
}

