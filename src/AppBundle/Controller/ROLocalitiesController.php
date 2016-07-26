<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ROLocalitiesController extends Controller
{

    public function getCountyNameAction($countyId)
    {
        $county = $this->getDoctrine()->getManager()->getRepository('AppBundle:ROCounty')->find($countyId);
        if ($county) {
            return new Response($county->getName());
        }

        return new Response();
    }

    public function getCityNameAction($cityId)
    {
        $city = $this->getDoctrine()->getManager()->getRepository('AppBundle:ROCity')->find($cityId);
        if ($city) {
            return new Response($city->getName());
        }

        return new Response();
    }

}