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
        $domainOptions = array(
            'class' => 'AppBundle:Domain',
            'empty_value' => 'No Domain',
            'expanded' => false,
            'multiple' => false,
            'by_reference' => false,
            'required' => false,
            'btn_add' => false,
        );

        $form->add('name')
            ->add('description', 'sonata_simple_formatter_type', array(
              'format' => 'richhtml',
                'required' => false
            ))
            ->add('domain', 'sonata_type_model', $domainOptions);

        $pDomainId = $this->request->query->get('pDomainId');

        if (isset($pDomainId)) {
            $form->remove('domain');
            if ($pDomainId > 0) {
                $em = $this->modelManager->getEntityManager('AppBundle:Domain');
                $query = $em->createQueryBuilder('d')
                    ->select('d')
                    ->from('AppBundle:Domain', 'd')
                    ->where('d.id = :domainId')
                    ->setParameter('domainId', $pDomainId);

                $domainOptions['query'] = $query;
                $form->add('domain', 'sonata_type_model', $domainOptions);
            }
        }
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