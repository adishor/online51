<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class UserController extends Controller
{

    public function getCitiesAction(Request $request)
    {
        $jsonCities = [];

        $countyId = $request->request->get('countyId');
        $county = $this->getDoctrine()->getRepository('AppBundle:ROCounty')->find($countyId);
        if ($county) {
            $cities = $county->getCities();

            foreach ($cities as $city) {
                $jsonCities[$city->getId()] = $city->getName();
            }
        }

        return new Response(json_encode($jsonCities), 200);
    }

    public function deleteAction($id)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if ($this->getUser()->getId() == $id) {
            $this->addFlash(
              'sonata_flash_error', $this->get('translator')->trans('You cannot delete your own account')
            );
            return $this->redirectTo($object);
        }

        return parrent::deleteAction($id);
    }

    public function batchActionDelete(ProxyQueryInterface $query)
    {

        $request = $this->getRequest();
        $idx = $request->request->get('idx');

        if (empty($idx) && $request->request->has('all_elements') && $request->request->get('all_elements') == 'on') {
            $users = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataUserBundle:User')
              ->findBy(array('deleted' => FALSE));
            $idx = array();
            foreach ($users as $user) {
                $idx[] = $user->getId();
            }
        }

        foreach ($idx as $id) {
            if ($this->getUser()->getId() == $id) {
                $this->addFlash(
                  'sonata_flash_error', $this->get('translator')->trans('You cannot delete your own account')
                );

                return $this->redirect($this->admin->generateUrl('list'));
            }
        }

        return parent::batchActionDelete($query);
    }

}