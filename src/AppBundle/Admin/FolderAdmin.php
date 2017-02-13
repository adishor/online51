<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 11/02/2017
 * Time: 16:33
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class FolderAdmin extends Admin
{
    public function configureFormFields(FormMapper $form)
    {

        $disabled = (boolean)($this->getSubject()->getDeleted());

        $form->add('name', null, array(
            'disabled' => $disabled
        ))
        ->add('description', 'sonata_simple_formatter_type', array(
            'format' => 'richhtml',
            'required' => false,
            'disabled' => $disabled
        ))
        ->add('subdomain', 'entity',  array(
            'class' => 'AppBundle:SubDomain',
            'choices' => $this->getFolderChoices($form),
            'empty_value' => 'No SubDomain',
            'expanded' => false,
            'multiple' => false,
            'by_reference' => false,
            'required' => false,
            'disabled' => $disabled
        ));
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
            ->add('subdomain')
            ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name')
            ->add('subdomain')
            ->add('deleted');
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }


    public function getFolderChoices(FormMapper $formMapper)
    {
        //get all subdomains that are associated
        $domainEm = $formMapper->getAdmin()->getModelManager()->getEntityManager('AppBundle:Domain');
        $domains = $domainEm->getRepository('AppBundle:Domain')->findAll();
        $choices = [];
        foreach ($domains as $domain) {
            $subdomains = [];
            foreach ($domain->getSubdomains() as $subdomain) {
                if (!$subdomain->getDeleted()) {
                    $subdomains[] = $subdomain;
                }
            }
            $choices[$domain->getName()] = $subdomains;
        }
        //get all subdomains that are not associated
        $subdomainEm = $formMapper->getAdmin()->getModelManager()->getEntityManager('AppBundle:SubDomain');
        $noDomainSubdomains = $subdomainEm->getRepository('AppBundle:SubDomain')->createQueryBuilder('s')
            ->where('s.domain is NULL')
            ->andWhere('s.deleted = 0')
            ->getQuery()
            ->getResult();
        $choices['No Domain'] = $noDomainSubdomains;
        return $choices;
    }
}