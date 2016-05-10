<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\EqualType;
use Sonata\CoreBundle\Form\Type\BooleanType;

class OrderAdmin extends Admin
{
    protected $isActivated;

    protected function configureFormFields(FormMapper $form)
    {
        $disabled = ($this->getSubject()->getDeleted() ||
          ($this->getSubject()->getFirstActive())) ? TRUE : FALSE;
        $disabledActive = ($this->getSubject()->getDeleted()) ? TRUE : FALSE;
        $disabledUser = ($this->getSubject()->getId()) ? TRUE : FALSE;

        $queryUser = $this->modelManager
          ->getEntityManager('ApplicationSonataUserBundle:User')
          ->createQueryBuilder()
          ->select('u')
          ->from('ApplicationSonataUserBundle:User', 'u')
          ->where('u.deleted = 0')
          ->andWhere('NOT u.roles LIKE :role')
          ->setParameter('role', '%"ROLE_SUPER_ADMIN"%');

        $querySubscription = $this->modelManager
          ->getEntityManager('AppBundle:Subscription')
          ->createQueryBuilder()
          ->select('s')
          ->from('AppBundle:Subscription', 's')
          ->where('s.deleted = 0');

        $queryDomain = $this->modelManager
          ->getEntityManager('AppBundle:Domain')
          ->createQueryBuilder()
          ->select('d')
          ->from('AppBundle:Domain', 'd')
          ->where('d.deleted = 0');

        $form->add('user', null, array(
              'query_builder' => $queryUser,
              'disabled' => $disabledUser
          ))
          ->add('subscription', 'sonata_type_model', array(
              'query' => $querySubscription,
              'empty_value' => 'subscription.no_subscription',
              'required' => false,
              'btn_add' => false,
              'disabled' => $disabled
          ))
          ->add('creditValue', null, array(
              'disabled' => $disabled
          ))
          ->add('valabilityDays', null, array(
              'disabled' => $disabled
          ))
          ->add('price', null, array(
              'required' => false,
              'disabled' => $disabled
          ))
          ->add('domainAmount', 'hidden', array(
              'disabled' => $disabled
          ))
          ->add('domains', 'sonata_type_model', array(
              'query' => $queryDomain,
              'expanded' => true,
              'multiple' => true,
              'btn_add' => false,
              'disabled' => $disabled
          ))
          ->add('mentions', null, array(
              'required' => true,
              'disabled' => $disabledActive
          ))
          ->add('active', null, array(
              'disabled' => $disabledActive
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('user')
          ->add('subscription')
          ->add('active', null, array(), null, array('choices_as_values' => true))
          ->add('domains')
          ->add('deleted', null, array(), null, array('choices_as_values' => true));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id')
          ->add('active')
          ->add('startDate')
          ->add('endingDate')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('price')
          ->add('user')
          ->add('domains')
          ->add('subscription')
          ->add('createdAt')
          ->add('approvedBy')
          ->add('approvedDate')
          ->add('invoice')
          ->add('deleted');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('id')
          ->add('user')
          ->add('active')
          ->add('startDate')
          ->add('endingDate')
          ->add('creditValue')
          ->add('valabilityDays')
          ->add('price')
          ->add('domains')
          ->add('subscription')
          ->add('createdAt')
          ->add('mentions')
          ->add('approvedBy')
          ->add('approvedDate')
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

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('getDomains', 'getDomains');
    }

    public function prePersist($object)
    {
        $object->setFirstActive(false);
        $this->preUpdate($object);
    }

    public function postPersist($object)
    {
        $this->postUpdate($object);
    }

    public function preUpdate($object)
    {
        $object->setDomainAmount(count($object->getDomains()));

        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $original = $em->getUnitOfWork()->getOriginalEntityData($object);
        $this->isActivated = (empty($original)) ? FALSE : $original['active'];
    }

    public function postUpdate($object)
    {
        $em = $this->configurationPool->getContainer()->get('Doctrine')->getManager();
        $user = $this->configurationPool->getContainer()->get('security.context')->getToken()->getUser();
        if ($object->getActive() && !$object->getFirstActive()) {
            $this->configurationPool->getContainer()->get('app.mailer')->sendOrderConfirmationMessage($object);

            $startDate = new \DateTime();
            $endDate = new \DateTime();
            $endDate->add(new \DateInterval('P' . $object->getValabilityDays() . 'D'));
            $object->setStartDate($startDate);
            $object->setEndingDate($endDate);
            $object->setFirstActive(TRUE);
            $object->setApprovedBy($user);
            $object->setApprovedDate(new \DateTime());
            $object->setMentions($this->configurationPool->getContainer()->get('translator')->trans('order.approved-by-administrator'));
        } else {
            $object->setMentions($this->configurationPool->getContainer()->get('translator')->trans('order.modified-by-administrator'));
        }
        $object->setLastModifiedBy($user);

        if ($object->getActive() && !$this->isActivated) {
            $object->getUser()->setCreditsTotal($object->getUser()->getCreditsTotal() + $object->getCreditValue());
            $object->getUser()->setLastCreditUpdate(new \DateTime());
        }

        if (!$object->getActive() && $this->isActivated) {
            $creditTotal = $object->getUser()->getCreditsTotal() - $object->getCreditValue();
            $creditTotal = ($creditTotal < 0) ? 0 : $creditTotal;

            $object->getUser()->setCreditsTotal($creditTotal);
            $object->getUser()->setLastCreditUpdate(new \DateTime());
        }

        $em->persist($object);
        $em->flush();
    }

    public function preRemove($object)
    {
        if ($object->getActive() && !$object->getDeleted()) {
            $creditTotal = $object->getUser()->getCreditsTotal() - $object->getCreditValue();
            $creditTotal = ($creditTotal < 0) ? 0 : $creditTotal;

            $object->getUser()->setCreditsTotal($creditTotal);
            $object->getUser()->setLastCreditUpdate(new \DateTime());

            $object->setMentions($this->configurationPool->getContainer()->get('translator')->trans('order.removed-by-administrator'));
        }
    }

    public function postRemove($object)
    {
        if (!$object->getFirstActive()) {
            $this->configurationPool->getContainer()->get('app.mailer')->sendPendingOrderRemoveMessage($object);
        }
        $em = $this->configurationPool->getContainer()->get('Doctrine')->getManager();
        $object->setLastModifiedBy($this->configurationPool->getContainer()->get('security.context')->getToken()->getUser());

        $em->persist($object);
        $em->flush();
    }

    public function preBatchAction($actionName, \Sonata\AdminBundle\Datagrid\ProxyQueryInterface $query, array &$idx, $allElements)
    {
        if (empty($idx) && $allElements && $actionName === 'delete') {
            $idx = array();
            $query->select('DISTINCT ' . $query->getRootAlias());
            foreach ($query->getQuery()->iterate() as $pos => $object) {
                $idx[] = $object[0]->getId();
            }
        }
        foreach ($idx as $id) {
            $this->preRemove($this->getModelManager()->getEntityManager($this->getClass())->getRepository('AppBundle:Order')->find($id));
        }

        parent::preBatchAction($actionName, $query, $idx, $allElements);
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

}