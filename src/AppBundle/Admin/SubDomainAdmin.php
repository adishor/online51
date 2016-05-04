<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class SubDomainAdmin extends Admin
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
            'class' => 'AppBundle:Domain',
            'empty_value' => 'No Domain',
            'expanded' => false,
            'multiple' => false,
            'by_reference' => false,
            'required' => false,
            'btn_add' => false,
            'disabled' => $disabled
        );

        $form->add('name', null, array(
                'disabled' => $disabled
            ))
            ->add('description', 'sonata_simple_formatter_type', array(
                'format' => 'richhtml',
                'required' => false,
                'disabled' => $disabled
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
                ->add('documents')
                ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
                ->add('domain')
                ->add('documents')
                ->add('deleted');
    }

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
                ->add('description', 'html')
                ->add('domain')
                ->add('documents')
                ->add('deleted')
                ->add('deletedAt');
    }

    public function getFilterParameters()
    {
        $parameters = parent::getFilterParameters();

        if (!array_key_exists("deleted", $parameters)) {
            $parameters['deleted'] = array (
                'type' => EqualType::TYPE_IS_EQUAL,
                'value' => BooleanType::TYPE_NO
            );
        }

        return $parameters;
    }

    public function prePersist($object)
    {
        $object->setDeleted(false);
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }
}
