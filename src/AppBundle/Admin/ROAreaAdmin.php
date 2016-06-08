<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class ROAreaAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $form->add('name', null, array(
              'disabled' => $disabled
          ))
          ->add('counties', null, array(
              'expanded' => false,
              'multiple' => true,
              'by_reference' => false,
              'required' => false,
              'disabled' => $disabled
          ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
          ->add('counties')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('counties')
          ->add('deleted');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('counties')
          ->add('deleted')
          ->add('deletedAt')
        ;
    }

    public function getFilterParameters()
    {
        $parameters = parent::getFilterParameters();

        if (!array_key_exists("deleted", $parameters)) {
            $parameters['deleted'] = array(
                'type' => EqualType::TYPE_IS_EQUAL,
                'value' => BooleanType::TYPE_NO
            );
        }

        return $parameters;
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

}