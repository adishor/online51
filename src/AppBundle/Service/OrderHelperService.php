<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Order;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
                $order->addDomain($this->entityManager->getRepository('AppBundle:Domain')->find($key));
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
        $this->session->getFlashBag()->add('order-success', 'success.order');

        return true;
    }

    public function getActiveCreditTotal($userId)
    {
        $orderRepository = $this->entityManager->getRepository('AppBundle:Order');
        $activeOrders = $orderRepository->findAllActiveOrders($userId);
        $bonusOrders = $orderRepository->findAllBonusOrders($userId);
        $sum = 0;
        if (null !== $activeOrders) {
            foreach ($activeOrders as $order) {
                $sum += $order->getCreditValue();
            }
        }
        if (null !== $bonusOrders) {
            foreach ($bonusOrders as $order) {
                $sum += $order->getCreditValue();
            }
        }

        return $sum;
    }

    public function removeOrder($orderId)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $order = $this->entityManager->getRepository('AppBundle:Order')->find($orderId);
        if (null === $order) {

            throw new NotFoundHttpException();
        }
        if (($user->getId()) !== ($order->getUser()->getId())) {

            throw new AccessDeniedHttpException();
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();
        $order->setMentions($this->translator->trans('subscription.canceled'));
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('order-success', 'success.order-remove');
    }

    public function addInfoToUnlockedDocuments($unlockedDocuments)
    {
        foreach ($unlockedDocuments as $key => $document) {
            $unlockedDocuments[$key]['subject'] = 'order.document';
            $unlockedDocuments[$key]['orderId'] = '';
            $unlockedDocuments[$key]['sign'] = '-';
        }

        return $unlockedDocuments;
    }

    public function getDocumentObjects($unlockedDocuments)
    {
        $documentObjects = [];
        foreach ($unlockedDocuments as $document) {
            $documentObjects[$document['id']] = $this->entityManager->getRepository('Application\Sonata\MediaBundle\Entity\Media')->find($document['id']);
        }

        return $documentObjects;
    }

    public function addInfoToHistoryOrders($allHistoryOrders)
    {
        foreach ($allHistoryOrders as $key => $order) {
            if ($order['title'] !== null) {
                $allHistoryOrders[$key]['subject'] = 'order.subscription';
                $allHistoryOrders[$key]['orderId'] = $order['id'];
            } else {
                $allHistoryOrders[$key]['subject'] = 'order.credits';
                $allHistoryOrders[$key]['orderId'] = '';
            }
            $allHistoryOrders[$key]['sign'] = '+';
        }

        return $allHistoryOrders;
    }

    public function prepareCreditHistory($allHistoryOrders, $unlockedDocuments)
    {
        $creditHistoryItems = array_merge($allHistoryOrders, $unlockedDocuments);
        $expireDates = [];
        $now = new \DateTime;
        foreach ($creditHistoryItems as $key => $value) {
            $expireDates[$key] = $value['unlockDate'];
            if ($now > $value['expireDate']) {
                $creditHistoryItems[$key]['status'] = 'order.expired';
                $creditHistoryItems[$key]['sign'] = '*';
            } else {
                $creditHistoryItems[$key]['status'] = 'order.active';
            }
        }
        array_multisort($expireDates, SORT_DESC, $creditHistoryItems);

        return $creditHistoryItems;
    }

    public function getCreditHistory($userId)
    {

        $unlockedDocuments = $this->addInfoToUnlockedDocuments($this->entityManager->getRepository('AppBundle:CreditsUsage')->findAllUserDocuments($userId));
        $allHistoryOrders = $this->addInfoToHistoryOrders($this->entityManager->getRepository('AppBundle:Order')->findAllHistoryOrders($userId));

        return $this->prepareCreditHistory($allHistoryOrders, $unlockedDocuments);
    }

}