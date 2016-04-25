<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{

    /**
     * @Route("/information/", name="show_orders")
     */
    public function showOrderAction(Request $request)
    {
        $post = $request->request;
        if ($post->has('subscriptionId')) {
            if (!$this->get('app.order_helper')->addSubscription($post->get('subscriptionId'), $post->get('domains'))) {

                return $this->redirectToRoute('subscriptions');
            }
        }

        $user = $this->getUser();
        $this->get('app.user_helper')->updateValidUserCredits();
        $activeOrders = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllActiveOrders($user->getId());
        $bonusOrders = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllBonusOrders($user->getId());
        $pendingOrders = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllPendingOrders($user->getId());
        $usedCredits = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->findAllUsedCredits($user->getId());


        return $this->render('order/order_page.html.twig', array(
              'activeOrders' => $activeOrders,
              'bonusOrders' => $bonusOrders,
              'pendingOrders' => $pendingOrders,
              'usedCredits' => $usedCredits,
        ));
    }

    /**
     * @Route("/remove-subscription/", name="remove_subscription")
     */
    public function removeSubscriptionAction()
    {


        return $this->redirectToRoute('subscriptions');
    }

}