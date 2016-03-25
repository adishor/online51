<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserSubscriptionAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('user')
                ->add('subscription')
                ->add('active')
                ->add('subscriptionDomains');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
                ->add('subscription')
                ->add('active')
                ->add('subscriptionDomains');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('user')
                ->add('subscription')
                ->addIdentifier('active')
                ->add('subscriptionDomains')
                ->add('createdAt')
                ->add('approvedBy')
                ->add('approvedDate');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
                ->add('subscription')
                ->add('active')
                ->add('subscriptionDomains')
                ->add('createdAt')
                ->add('approvedBy')
                ->add('approvedDate');
    }
}

