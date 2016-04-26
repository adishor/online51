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
          ->add('document')
          ->add('credit', null, array('read_only' => true, 'required' => false))
          ->add('mentions', null, array('required' => false))
          ->add('expireDate', null, array('required' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
          ->add('document')
          ->add('credit')
          ->add('mentions')
          ->add('createdAt')
          ->add('expireDate');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id')
          ->addIdentifier('createdAt')
          ->add('user')
          ->add('document')
          ->add('credit')
          ->add('mentions')
          ->add('expireDate');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
          ->add('document')
          ->add('credit')
          ->add('mentions')
          ->add('createdAt')
          ->add('expireDate');
    }

}