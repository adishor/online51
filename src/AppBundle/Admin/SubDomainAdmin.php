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
              'format' => 'richhtml',
              'required' => false
          ))
          ->add('domain')
          ->add('documents', 'sonata_type_model', array(
              'expanded' => false,
              'multiple' => true,
              'by_reference' => false,
              'required' => false
        ));
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
          ->add('domain')
          ->add('documents');
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('domain')
          ->add('documents');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('description')
          ->add('domain')
          ->add('documents');
    }

}