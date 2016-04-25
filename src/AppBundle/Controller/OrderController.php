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

        $user = $this->getUser();
        $this->get('app.user_helper')->updateValidUserCredits();
        $activeOrders = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllActiveOrders($user->getId());
        $bonusOrders = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllBonusOrders($user->getId());
        $activeOrderTotal = $this->get('app.order_helper')->getActiveCreditTotal($activeOrders, $bonusOrders);
        $pendingOrders = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllPendingOrders($user->getId());
        $unlockedDocuments = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->findAllValidUserDocuments($user->getId());
        $documentObjects = [];
        foreach ($unlockedDocuments as $document) {
            $documentObjects[$document['id']] = $this->getDoctrine()->getManager()->getRepository('Application\Sonata\MediaBundle\Entity\Media')->find($document['id']);
        }
        $usedCredits = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->findAllUsedCredits($user->getId());


        return $this->render('order/order_page.html.twig', array(
              'activeOrders' => $activeOrders,
              'bonusOrders' => $bonusOrders,
              'activeOrderTotal' => $activeOrderTotal,
              'pendingOrders' => $pendingOrders,
              'unlockedDocuments' => $unlockedDocuments,
              'documentObjects' => $documentObjects,
              'usedCredits' => $usedCredits,
        ));
    }

    /**
     * @Route("/information/add-subscription/", name="add_subscription")
     */
    public function addSubscriptionAction(Request $request)
    {
        $post = $request->request;
        if ($post->has('subscriptionId')) {
            if (!$this->get('app.order_helper')->addSubscription($post->get('subscriptionId'), $post->get('domains'))) {

                return $this->redirectToRoute('subscriptions');
            }
        }


        return $this->redirect($this->generateUrl('show_orders') . '#doc-pending');
    }

    /**
     * @Route("/information/remove-order/{orderId}", name="remove_order")
     */
    public function removeOrderAction($orderId)
    {
        $this->get('app.order_helper')->removeOrder($orderId);

        return $this->redirect($this->generateUrl('show_orders') . '#doc-pending');
    }

}