<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserAdminController extends Controller
{
    public function getCitiesAction(Request $request)
    {
        $html = "";

        $countyId = $request->request->get('countyId');
        $county = $this->getDoctrine()->getRepository('AppBundle:ROCounty')->find($countyId);
        if ($county) {
            $cities = $county->getCities();

            foreach ($cities as $city) {
                $html .= '<option value="'. $city->getId() .'">'. $city->getName() . '</option>';
            }
        }

        return new Response($html, 200);
    }
}
