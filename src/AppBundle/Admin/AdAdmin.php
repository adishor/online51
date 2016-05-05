<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AdAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('title')
          ->add('uploadImage', 'sonata_admin_image_file', array(
              'required' => false
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('title');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('title');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('title')
          ->add('uploadImage', null, array(
              'template' => 'sonata/fieldType/show_admin_image_file_field.html.twig'
        ));
    }

    public function getFormTheme()
    {
        return array_merge(
          parent::getFormTheme(), array('sonata/fieldType/admin_image_file_field.html.twig')
        );
    }

}