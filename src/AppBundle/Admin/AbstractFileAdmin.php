<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 12/02/2017
 * Time: 15:29
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Application\Sonata\MediaBundle\Entity\Media;

abstract class AbstractFileAdmin extends Admin
{
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
        if ($name == "list") {
            return 'sonata/base_list.html.twig';
        }
        return parent::getTemplate($name);
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('getFolders', 'getFolders');
    }

}