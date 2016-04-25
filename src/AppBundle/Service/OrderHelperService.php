<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Order;
use AppBundle\Entity\Cart;

class OrderHelperService
{
    protected $entityManager;
    protected $translator;
    protected $tokenStorage;
    protected $session;

    public function __construct(EntityManager $entityManager, TranslatorInterface $translator, TokenStorage $tokenStorage, Session $session)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    public function addSubscription($subscriptionId, $domains = null)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $order = new Order();

        $subscription = $this->entityManager->getRepository('AppBundle:Subscription')->find($subscriptionId);
        if (count($subscription->getDomains()) === $subscription->getDomainAmount()) {
            foreach ($subscription->getDomains() as $domain) {
                $order->addDomain($domain);
            }
        } else {
            if (null === $domains) {
                $this->session->getFlashBag()->add('order-error', 'order.error.select-all-domains');

                return false;
            }
        }

        if (null !== $domains) {
            if (count($domains) !== $subscription->getDomainAmount()) {
                $this->session->getFlashBag()->add('order-error', 'order.error.select-all-domains');

                return false;
            }
            foreach ($domains as $key => $value) {
                if ('on' === $value) {
                    $order->addDomain($this->entityManager->getRepository('AppBundle:Domain')->find($key));
                }
            }
        }

        $order->setUser($user);
        $order->setSubscription($subscription);
        $order->setCreditValue($subscription->getCredit());
        $order->setPrice($subscription->getPrice());
        $order->setValabilityDays($subscription->getValability() * 365);
        $order->setDomainAmount($subscription->getDomainAmount());
        $order->setMentions($this->translator->trans('subscription.bought'));
        $order->setActive(false);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return true;
    }

}