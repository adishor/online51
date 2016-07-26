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
            ->getRepository('AppBundle:Subscription')->findBy(array('deleted' => false));
        $isUserException = false;
        if ($this->getUser()) {
            $isUserException = $this->get('app.user')->getIsUserException($this->getUser()->getId());
        }

        return $this->render('subscription/show.html.twig', array(
              'subscriptions' => $subscriptions,
              'isUserException' => $isUserException,
        ));
    }

}