<?php

namespace AppBundle\Provider;

use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentProvider extends FileProvider
{

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
    {
        $disabled = ($formMapper->getAdmin()->getSubject()->getDeleted()) ? TRUE : FALSE;

        $formMapper
            ->add('title', null, array(
                'disabled' => $disabled
            ))
            ->add('creditValue', null, array(
                'disabled' => $disabled
            ))
            ->add('valabilityDays', null, array(
                'disabled' => $disabled
            ))
            ->add('subdomain', 'entity', array(
                'expanded' => false,
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'class' => 'AppBundle:SubDomain',
                'choices' => $this->getChoices($formMapper),
                'disabled' => $disabled
            ))
            ->add('binaryContent', 'file', array(
                'required' => false,
                'disabled' => $disabled
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildCreateForm(FormMapper $formMapper)
    {
        $formMapper
          ->add('title')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('subdomain', 'entity', array(
              'expanded' => false,
              'multiple' => false,
              'by_reference' => true,
              'required' => false,
              'class' => 'AppBundle:SubDomain',
              'choices' => $this->getChoices($formMapper),
          ))
          ->add('binaryContent', 'file', array(
              'constraints' => array(
                  new NotBlank(),
                  new NotNull(),
              ),
        ));
    }

    public function getChoices(FormMapper $formMapper)
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