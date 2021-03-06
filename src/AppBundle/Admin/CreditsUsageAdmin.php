<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\AdminBundle\Route\RouteCollection;

class CreditsUsageAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;

        $queryUser = $this->modelManager
          ->getEntityManager('ApplicationSonataUserBundle:User')
          ->createQueryBuilder()
          ->select('u')
          ->from('ApplicationSonataUserBundle:User', 'u')
          ->where('NOT u.roles LIKE :role')
          ->andWhere('u.deleted = 0')
          ->setParameter('role', '%"ROLE_SUPER_ADMIN"%');

        $queryDocument = $this->modelManager
          ->getEntityManager('AppBundle:Document')
          ->createQueryBuilder()
          ->select('d')
          ->from('AppBundle:Document', 'd')
          ->where('d.deleted = 0');

        $queryVideo = $this->modelManager
          ->getEntityManager('AppBundle:Video')
          ->createQueryBuilder()
          ->select('v')
          ->from('AppBundle:Video', 'v')
          ->where('v.deleted = 0');

        $form->add('user', null, array(
              'query_builder' => $queryUser,
              'disabled' => $disabled
          ))
          ->add('document', null, array(
              'query_builder' => $queryDocument,
              'empty_value' => 'No Document',
              'required' => false,
              'disabled' => ($this->getSubject()->getFormular()) ? true : $disabled
          ))
          ->add('video', null, array(
              'query_builder' => $queryVideo,
              'empty_value' => 'No Video',
              'required' => false,
              'disabled' => ($this->getSubject()->getFormular()) ? true : $disabled
          ))
          ->add('formular', null, array(
              'empty_value' => 'No Formular',
              'required' => false,
              'disabled' => TRUE
          ))
          ->add('credit', null, array(
              'disabled' => $disabled
          ))
          ->add('mentions', null, array(
              'required' => false,
              'disabled' => $disabled
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
          ->add('document')
          ->add('formular')
          ->add('video')
          ->add('credit')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id')
          ->add('createdAt')
          ->add('user')
          ->add('document')
          ->add('formular')
          ->add('video')
          ->add('media')
          ->add('credit')
          ->add('mentions')
          ->add('expireDate')
          ->add('deleted')
          ->add('_action', null, array(
              'actions' => array(
                  'edit' => array(),
            )))
        ;
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('user')
          ->add('document')
          ->add('formular')
          ->add('video')
          ->add('media', null, array(
              'template' => 'sonata/credits_usage_base_show_field.html.twig',
          ))
          ->add('credit')
          ->add('mentions')
          ->add('createdAt')
          ->add('expireDate')
          ->add('deleted')
          ->add('deletedAt');
    }

    public function getTemplate($name)
    {
        if ($name == "edit") {
            return 'sonata/base_edit.html.twig';
        }
        return parent::getTemplate($name);
    }

    public function prePersist($object)
    {

        if ($object->getDocument()) {
            $expireDate = new \DateTime();
            $expireDate->add(new \DateInterval('P' . $object->getDocument()->getValabilityDays() . 'D'));
            $object->setExpireDate($expireDate);
            $object->setUsageType(\AppBundle\Entity\CreditsUsage::TYPE_DOCUMENT);
            $object->setMedia($object->getDocument()->getMedia());
        }

        if ($object->getVideo()) {
            $expireDate = new \DateTime();
            $expireDate->add(new \DateInterval('P' . $object->getVideo()->getValabilityDays() . 'D'));
            $object->setExpireDate($expireDate);
            $object->setUsageType(\AppBundle\Entity\CreditsUsage::TYPE_VIDEO);
            $object->setMedia($object->getVideo()->getMedia());
        }
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

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('getDocumentCreditValue', 'getDocumentCreditValue');
        $collection->add('getVideoCreditValue', 'getVideoCreditValue');
    }

}