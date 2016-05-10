<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Application\Sonata\MediaBundle\Entity\Media;

class DocumentAdmin extends Admin
{

    public function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $subdomainsOptions = array(
            'expanded' => false,
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'class' => 'AppBundle:SubDomain',
            'choices' => $this->getSubdomainChoices($form),
            'disabled' => $disabled
        );

        $queryMedia = $this->modelManager
          ->getEntityManager('ApplicationSonataMediaBundle:Media')
          ->createQueryBuilder()
          ->select('m')
          ->from('ApplicationSonataMediaBundle:Media', 'm')
          ->leftJoin('m.document', 'd')
          ->where('m.deleted = 0')
          ->andWhere('m.mediaType = :mediaType')
          ->setParameter('mediaType', Media::DOCUMENT_TYPE)
          ->andWhere('d.id is null or d.id = :documentId')
          ->setParameter('documentId', $this->getSubject()->getId());

        $form->add('name', null, array(
              'disabled' => $disabled
          ))
          ->add('creditValue', null, array(
              'disabled' => $disabled
          ))
          ->add('valabilityDays', null, array(
              'disabled' => $disabled
          ))
          ->add('subdomain', 'entity', $subdomainsOptions)
          ->add('media', 'sonata_type_model', array(
              'query' => $queryMedia,
              'disabled' => $disabled,
              'required' => true,
            ), array(
              'link_parameters' => array(
                  'context' => 'default',
                  'provider' => 'sonata.media.provider.file',
              )
        ));
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name')
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

    public function configureShowFields(ShowMapper $show)
    {
        $show->add('name')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain')
          ->add('media')
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

    public function getSubdomainChoices(FormMapper $formMapper)
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

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

}