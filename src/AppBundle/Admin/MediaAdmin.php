<?php

namespace AppBundle\Admin;

use Sonata\MediaBundle\Admin\ORM\MediaAdmin as SonataMediaAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class MediaAdmin extends SonataMediaAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper
          ->add('name')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    protected function configureListFields(ListMapper $list)
    {

        $list->addIdentifier('name')
          ->add('deleted');
    }

    protected function configureShowFields(ShowMapper $show)
    {


        $show->add('name')
          ->add('deleted')
          ->add('deletedAt')
          ->add('binaryContent');
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

}