<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
}
