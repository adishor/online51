<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class SubscriptionAdmin extends Admin
{

    public function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $queryDomain = $this->modelManager
          ->getEntityManager('AppBundle:Domain')
          ->createQueryBuilder()
          ->select('d')
          ->from('AppBundle:Domain', 'd')
          ->where('d.deleted = 0');

        $domainOptions = array(
            'query' => $queryDomain,
            'expanded' => false,
            'multiple' => true,
            'by_reference' => false,
            'required' => false,
            'disabled' => $disabled
        );

        if ($this->getSubject()->getDeleted()) {
            $domainOptions['btn_add'] = FALSE;
        }

        $form->add('name', null, array(
              'disabled' => $disabled
          ))
          ->add('intro', 'sonata_simple_formatter_type', array(
              'format' => 'richhtml',
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('description', 'sonata_simple_formatter_type', array(
              'format' => 'richhtml',
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('price', null, array(
              'disabled' => $disabled
          ))
          ->add('credit', null, array(
              'disabled' => $disabled
          ))
          ->add('valability', null, array(
              'disabled' => $disabled
          ))
          ->add('domainAmount', null, array(
              'disabled' => $disabled
          ))
          ->add('domains', 'sonata_type_model', $domainOptions);
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
          ->add('domains')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
          ->add('price')
          ->add('credit')
          ->add('valability')
          ->add('domainAmount')
          ->add('domains')
          ->add('deleted');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('intro', 'html')
          ->add('description', 'html')
          ->add('price')
          ->add('credit')
          ->add('valability')
          ->add('domainAmount')
          ->add('domains')
          ->add('deleted')
          ->add('deletedAt');
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