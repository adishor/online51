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
    public function showOrderAction()
    {

        $userId = $this->getUser()->getId();
        $orderRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order');
        $creditUsageRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage');
        $this->get('app.user_helper')->updateValidUserCredits();
        $unlockedDocuments = $creditUsageRepository->findAllUserDocuments($userId);

        return $this->render('order/order_page.html.twig', array(
              'activeOrders' => $orderRepository->findAllActiveUserOrders($userId),
              'bonusOrders' => $orderRepository->findAllActiveBonusUserOrders($userId),
              'pendingOrders' => $orderRepository->findAllPendingOrders($userId),
              'validDocuments' => $creditUsageRepository->findAllValidUserDocuments($userId),
              'unlockedDocuments' => $unlockedDocuments,
              'mediaObjects' => $this->get('app.order_helper')->getMediaObjects($unlockedDocuments),
              'creditHistoryItems' => $this->get('app.order_helper')->getCreditHistory($userId),
        ));
    }

    public function showCreditTotalsAction()
    {
        $userId = $this->getUser()->getId();
        $creditUsageRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage');
        $orderTotal = $this->get('app.order_helper')->getCreditTotal($userId);
        $usedCreditsTotal = $creditUsageRepository->findTotalUsedCredits($userId);
        $expiredCreditsTotal = $creditUsageRepository->findTotalExpiredCredits($userId);

        return $this->render('order/order_credit_totals.html.twig', array(
              'orderTotal' => $orderTotal,
              'usedCreditsTotal' => $usedCreditsTotal,
              'expiredCreditsTotal' => $expiredCreditsTotal,
        ));
    }

    /**
     * @Route("/information/add-subscription/", name="add_subscription")
     */
    public function addSubscriptionAction(Request $request)
    {
        $post = $request->request;
        if ($post->has('subscriptionId')) {
            if (!$this->get('app.order_helper')->addSubscription($post->get('subscriptionId'), $this->getParameter('billing_data'), $post->get('domains'))) {

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