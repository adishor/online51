<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $domains = $this->getDoctrine()->getManager()
                    ->getRepository('AppBundle:Domain')->findAll();

        $subscriptions = $this->getDoctrine()->getManager()
                    ->getRepository('AppBundle:Subscription')->findAll();

        return $this->render('default/index.html.twig', array(
            'domains' => $domains,
            'subscriptions' => $subscriptions
        ));
    }

    public function menuAction()
    {
        $domains = $this->getDoctrine()->getManager()
                    ->getRepository('AppBundle:Domain')->findAll();

        return $this->render('default/menu.html.twig', array(
            'domains' => $domains
        ));
    }
}
