<?php

namespace AppBundle\Provider;

use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

//use Doctrine\ORM\EntityManager;

class DocumentProvider extends FileProvider
{
//    protected $entityManager;
//
//    public function __construct($name, \Gaufrette\Filesystem $filesystem, \Sonata\MediaBundle\CDN\CDNInterface $cdn, \Sonata\MediaBundle\Generator\GeneratorInterface $pathGenerator, \Sonata\MediaBundle\Thumbnail\ThumbnailInterface $thumbnail, EntityManager $entityManager, array $allowedExtensions = array(), array $allowedMimeTypes = array(), \Sonata\MediaBundle\Metadata\MetadataBuilderInterface $metadata = null)
//    {
//        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes, $metadata);
//        $this->entityManager = $entityManager;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper)
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
          ->add('binaryContent', 'file', array('required' => false));
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
                $subdomains[] = $subdomain;
            }
            $choices[$domain->getName()] = $subdomains;
        }

        //get all subdomains that are not associated
        $subdomainEm = $formMapper->getAdmin()->getModelManager()->getEntityManager('AppBundle:SubDomain');
        $noDomainSubdomains = $subdomainEm->getRepository('AppBundle:SubDomain')->createQueryBuilder('s')
          ->where('s.domain is NULL')
          ->getQuery()
          ->getResult();
        $choices['No Domain'] = $noDomainSubdomains;


        return $choices;
    }

}