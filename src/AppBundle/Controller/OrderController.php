<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CreditsUsage;

class OrderController extends Controller
{

    /**
     * @Route("/information/", name="show_orders")
     */
    public function showOrderAction()
    {
        return $this->render('order/order_page.html.twig');
    }

    /**
     * @Route("/information/active-credits", name="show_active_credits")
     */
    public function showActiveCreditsAction(Request $request)
    {
        $userId = $this->getUser()->getId();
        $orderRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Order');
        $paginator = $this->get('knp_paginator');

        return $this->render('order/order_active_credits.html.twig', array(
              'activeOrders' => $paginator->paginate(
                $orderRepository->findAllActiveUserOrders($userId), $request->query->getInt('page-active-credits', 1), $this->getParameter('pagination')['active-credits'], array('pageParameterName' => 'page-active-credits')
              ),
              'bonusOrders' => $paginator->paginate($orderRepository->findAllActiveBonusUserOrders($userId), $request->query->getInt('page-active-credits', 1), $this->getParameter('pagination')['active-credits'], array('pageParameterName' => 'page-active-credits')),
            )
        );
    }

    /**
     * @Route("/information/pending-orders", name="show_pending_orders")
     */
    public function showPendingOrdersAction(Request $request)
    {
        return $this->render('order/order_pending_orders.html.twig', array(
              'pendingOrders' => $this->get('knp_paginator')->paginate($this->getDoctrine()->getManager()->getRepository('AppBundle:Order')->findAllPendingOrders($this->getUser()->getId()), $request->query->getInt('page-pending', 1), $this->getParameter('pagination')['pending'], array('pageParameterName' => 'page-pending')),
            )
        );
    }

    /**
     * @Route("/information/valid-documents", name="show_valid_documents")
     */
    public function showValidDocumentsAction(Request $request)
    {
        $userId = $this->getUser()->getId();
        $creditUsageRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage');
        $validDocuments = array_merge($creditUsageRepository->findAllValidUserDocuments($userId), $creditUsageRepository->findAllValidUserFormularDocuments($userId));

        return $this->render('order/order_valid_documents.html.twig', array(
              'validDocuments' => $this->get('knp_paginator')->paginate($validDocuments, $request->query->getInt('page-documents', 1), $this->getParameter('pagination')['documents'], array('pageParameterName' => 'page-documents')),
              'formularType' => CreditsUsage::TYPE_FORMULAR
            )
        );
    }

    /**
     * @Route("/information/credit-usage", name="show_credit_usage")
     */
    public function showCreditUsageAction(Request $request)
    {
        return $this->render('order/order_credit_usage.html.twig', array(
              'unlockedDocuments' => $this->get('knp_paginator')->paginate($this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->findAllUserDocuments($this->getUser()->getId()), $request->query->getInt('page-usage', 1), $this->getParameter('pagination')['usage'], array('pageParameterName' => 'page-usage')),
              'formularType' => CreditsUsage::TYPE_FORMULAR
            )
        );
    }

    /**
     * @Route("/information/credit-history", name="show_credit_history")
     */
    public function showCreditHistoryAction(Request $request)
    {
        return $this->render('order/order_credit_history.html.twig', array(
              'creditHistoryItems' => $this->get('knp_paginator')->paginate($this->get('app.order_helper')->getCreditHistory($this->getUser()->getId()), $request->query->getInt('page-history', 1), $this->getParameter('pagination')['history'], array('pageParameterName' => 'page-history')),
              'formularType' => CreditsUsage::TYPE_FORMULAR
            )
        );
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

        return $this->redirect($this->generateUrl('show_pending_orders'));
    }

    /**
     * @Route("/information/remove-order/{orderId}", name="remove_order")
     */
    public function removeOrderAction($orderId)
    {
        $this->get('app.order_helper')->removeOrder($orderId);

        return $this->redirect($this->generateUrl('show_pending_orders'));
    }

}