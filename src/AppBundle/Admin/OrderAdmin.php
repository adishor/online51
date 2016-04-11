<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class OrderAdmin extends Admin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('user')
                ->add('subscription', 'sonata_type_model', array(
                    'empty_value' => 'Fara abonament',
                    'required' => false,
                    'btn_add' => false
                ))
                ->add('creditValue')
                ->add('valabilityDays')
                ->add('price', null, array(
                    'required' => false
                ))
                ->add('domainAmount', 'hidden')
                ->add('domains', 'sonata_type_model', array(
                    'expanded' => true,
                    'multiple' => true,
                    'btn_add' => false,
                ))
                ->add('mentions', null, array(
                    'required' => false
                ))
                ->add('active');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
                ->add('subscription')
                ->add('active')
                ->add('domains');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id')
                ->add('user')
                ->add('active')
                ->add('startDate')
                ->add('endingDate')
                ->add('creditValue')
                ->add('valabilityDays')
                ->add('domains')
                ->add('subscription')
                ->add('createdAt')
                ->add('approvedBy')
                ->add('approvedDate');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
                ->add('subscription')
                ->add('active')
                ->add('domains')
                ->add('createdAt')
                ->add('approvedBy')
                ->add('approvedDate');
    }

    public function getTemplate($name)
    {
        if ( $name == "edit" )
        {
            return 'sonata/base_edit.html.twig' ;
        }
        return parent::getTemplate($name);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('getDomains', 'getDomains');
    }

    public function prePersist($object) {
        $this->preUpdate($object);
    }

    public function preUpdate($object) {
        $object->setDomainAmount(count($object->getDomains()));

        if ($object->getActive()) {
            $startDate = new \DateTime(date('Y-m-d'));
            $endDate =  new \DateTime(date('Y-m-d'));
            $endDate->add(new \DateInterval('P'.$object->getValabilityDays().'D'));
            $object->setStartDate($startDate);
            $object->setEndingDate($endDate);
        }
    }
}

