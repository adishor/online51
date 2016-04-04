<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdController extends Controller
{

    public function showAdsAction()
    {
        $ads = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Ad')->findAll();

        return $this->render('default/ads.html.twig', array(
              'ads' => $ads,
        ));
    }

}