<?php

namespace AppBundle\Admin;

use Sonata\MediaBundle\Admin\ORM\MediaAdmin as SonataMediaAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MediaAdmin extends SonataMediaAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
          ->add('title')
          ->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('title')
          ->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('title')
          ->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain');
    }

}