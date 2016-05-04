<?php

namespace AppBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{

    public function getDomainsAction(Request $request)
    {
        $response = [];

        $subscriptionId = $request->request->get('subscriptionId');
        if ($subscriptionId > 0) {
            $subscription = $this->getDoctrine()->getManager()->getRepository('AppBundle:Subscription')->find($subscriptionId);
            $domains = $subscription->getDomains();

            $response['credit'] = $subscription->getCredit();
            $response['domainAmount'] = $subscription->getDomainAmount();
            $response['valability'] = $subscription->getValability();
            $response['price'] = $subscription->getPrice();

            if (count($domains)) {
                $response['domains'] = [];
                foreach ($domains as $domain) {
                    $response['domains'][$domain->getId()] = $domain->getName();
                }
            }
        }

        return new Response(json_encode($response));
    }

}