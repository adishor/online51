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
        $userId = $this->getUser()->getId();
        $orderRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order');
        $creditUsageRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage');
        $this->get('app.user_helper')->updateValidUserCredits();
        $unlockedDocuments = $creditUsageRepository->findAllUserDocuments($userId);
        $validDocuments = array_merge($creditUsageRepository->findAllValidUserDocuments($userId), $creditUsageRepository->findAllValidUserFormularDocuments($userId));

        $location = '';
        foreach ($request->query as $key => $value) {
            if (strpos($key, "page-") !== FALSE) {
                $location = str_replace("page-", "", $key);
                break;
            }
        }

        $paginator = $this->get('knp_paginator');
        return $this->render('order/order_page.html.twig', array(
              'activeOrders' => $paginator->paginate($orderRepository->findAllActiveUserOrders($userId), $request->query->getInt('page-active-credits', 1), $this->getParameter('pagination')['active-credits'], array('pageParameterName' => 'page-active-credits')),
              'bonusOrders' => $paginator->paginate($orderRepository->findAllActiveBonusUserOrders($userId), $request->query->getInt('page-active-credits', 1), $this->getParameter('pagination')['active-credits'], array('pageParameterName' => 'page-active-credits')),
              'pendingOrders' => $paginator->paginate($orderRepository->findAllPendingOrders($userId), $request->query->getInt('page-pending', 1), $this->getParameter('pagination')['pending'], array('pageParameterName' => 'page-pending')),
              'validDocuments' => $paginator->paginate($validDocuments, $request->query->getInt('page-documents', 1), $this->getParameter('pagination')['documents'], array('pageParameterName' => 'page-documents')),
              'unlockedDocuments' => $paginator->paginate($unlockedDocuments, $request->query->getInt('page-usage', 1), $this->getParameter('pagination')['usage'], array('pageParameterName' => 'page-usage')),
              'mediaObjects' => $this->get('app.order_helper')->getMediaObjects($unlockedDocuments),
              'creditHistoryItems' => $paginator->paginate($this->get('app.order_helper')->getCreditHistory($userId), $request->query->getInt('page-history', 1), $this->getParameter('pagination')['history'], array('pageParameterName' => 'page-history')),
              'location' => $location
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
            if (!$this->get('app.order_helper')->addSubscription($post->get('subscriptionId'), $this->getParameter('billing_data'), $this->get('sonata.media.provider.file'), $post->get('domains'))) {

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