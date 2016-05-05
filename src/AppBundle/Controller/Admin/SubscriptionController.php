<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class SubscriptionController extends CRUDController
{

    public function deleteAction($id)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $order = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')
          ->findOneBy(array(
            'subscription' => $id,
            'deleted' => FALSE,
            'firstActive' => FALSE));
        if (count($order) > 0) {
            $this->addFlash(
              'sonata_flash_error', $this->get('translator')->trans("You cannot delete the subscription %name% because you have an order with this subscription waiting your approval", array('%name%' => $order->getSubscription()->getName()))
            );
            return $this->redirectTo($object);
        }

        return parent::deleteAction($id);
    }

    public function batchActionDelete(ProxyQueryInterface $query)
    {

        $request = $this->getRequest();
        $idx = $request->request->get('idx');

        if (empty($idx) && $request->request->has('all_elements') && $request->request->get('all_elements') == 'on') {
            $subs = $this->getDoctrine()->getManager()->getRepository('AppBundle:Subscription')
              ->findBy(array('deleted' => FALSE));
            $idx = array();
            foreach ($subs as $subscription) {
                $idx[] = $subscription->getId();
            }
        }

        foreach ($idx as $id) {
            $order = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')
              ->findOneBy(array(
                'subscription' => $id,
                'deleted' => FALSE,
                'firstActive' => FALSE));
            if (count($order) > 0) {
                $this->addFlash(
                  'sonata_flash_error', $this->get('translator')->trans("You cannot delete the subscription %name% because you have an order with this subscription waiting your approval", array('%name%' => $order->getSubscription()->getName()))
                );
                return $this->redirect($this->admin->generateUrl('list'));
            }
        }

        return parent::batchActionDelete($query);
    }

}