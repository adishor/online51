<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SubscriptionController extends Controller
{
    /**
     * @Route("/subscriptions", name="subscriptions")
     */
    public function showAction()
    {
        $subscriptions = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subscription')->findAll();

        return $this->render('subscription/show.html.twig', array(
              'subscriptions' => $subscriptions
        ));
    }
}