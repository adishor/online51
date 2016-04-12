<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DocumentAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('title')
          ->add('creditValue')
          ->add('valabilityDays');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('title')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('title')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('title')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain');
    }

}