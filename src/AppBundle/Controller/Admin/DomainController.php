<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class DomainController extends CRUDController
{

    public function deleteAction($id)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $order = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findPendingOrdersByDomain($id);
        if (count($order) > 0) {
            $domain = $this->getDoctrine()->getManager()->getRepository('AppBundle:Domain')->find($id);
            $this->addFlash(
              'sonata_flash_error', $this->get('translator')->trans("You cannot delete the domain %name% because you have an order with this domain waiting your approval", array('%name%' => $domain->getName()))
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
            $idx = array();
            $query->select('DISTINCT ' . $query->getRootAlias());
            foreach ($query->getQuery()->iterate() as $pos => $object) {
                $idx[] = $object[0]->getId();
            }
        }

        foreach ($idx as $id) {
            $order = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findPendingOrdersByDomain($id);
            if (count($order) > 0) {
                $domain = $this->getDoctrine()->getManager()->getRepository('AppBundle:Domain')->find($id);
                $this->addFlash(
                  'sonata_flash_error', $this->get('translator')->trans("You cannot delete the domain %name% because you have an order with this domain waiting your approval", array('%name%' => $domain->getName()))
                );

                return $this->redirect($this->admin->generateUrl('list'));
            }
        }

        return parent::batchActionDelete($query);
    }

}