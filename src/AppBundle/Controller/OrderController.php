<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Swift_Attachment;

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
              'activeOrders' => $orderRepository->findAllActiveOrders($userId),
              'bonusOrders' => $orderRepository->findAllBonusOrders($userId),
              'pendingOrders' => $orderRepository->findAllPendingOrders($userId),
              'validDocuments' => $creditUsageRepository->findAllValidUserDocuments($userId),
              'unlockedDocuments' => $unlockedDocuments,
              'documentObjects' => $this->get('app.order_helper')->getDocumentObjects($unlockedDocuments),
              'creditHistoryItems' => $this->get('app.order_helper')->getCreditHistory($userId),
        ));
    }

    public function showCreditTotalsAction()
    {
        $userId = $this->getUser()->getId();
        $activeOrderTotal = $this->get('app.order_helper')->getActiveCreditTotal($userId);
        $usedCreditsTotal = $this->getDoctrine()->getManager()->getRepository('AppBundle:CreditsUsage')->findTotalUsedCredits($userId);

        return $this->render('order/order_credit_totals.html.twig', array(
              'activeOrderTotal' => $activeOrderTotal,
              'usedCreditsTotal' => $usedCreditsTotal,
        ));
    }

    /**
     * @Route("/information/add-subscription/", name="add_subscription")
     */
    public function addSubscriptionAction(Request $request)
    {
        $post = $request->request;
        if ($post->has('subscriptionId')) {
            $order = $this->get('app.order_helper')->addSubscription($post->get('subscriptionId'), $post->get('domains'));
            if (!$order) {

                return $this->redirectToRoute('subscriptions');
            }
        }
        $this->get('knp_snappy.pdf')->generateFromHtml(
          $this->renderView('order/order_invoice_template.html.twig'), $this->getParameter('invoice_path')
        );
        $attachment = Swift_Attachment::fromPath($this->getParameter('invoice_path'))->setFilename('proforma.pdf');
        $this->get('app.mailer')->sendOrderInvoice($order, $attachment);


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