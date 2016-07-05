<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class DocumentGeneratedAdmin extends Admin
{

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain')
          ->add('media')
          ->add('deleted');
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
        if ($name == "list") {
            return 'sonata/base_list.html.twig';
        }
        return parent::getTemplate($name);
    }

}