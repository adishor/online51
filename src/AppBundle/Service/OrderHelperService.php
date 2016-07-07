<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\Order;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\MailerService;
use Swift_Attachment;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\CreditsUsage;

class OrderHelperService
{
    protected $entityManager;
    protected $translator;
    protected $tokenStorage;
    protected $session;
    protected $mailer;
    protected $snappyPdf;
    protected $templating;
    protected $invoiceDir;
    protected $invoiceName;

    public function __construct(EntityManager $entityManager, TranslatorInterface $translator, TokenStorage $tokenStorage, Session $session, MailerService $mailer, LoggableGenerator $snappyPdf, EngineInterface $templating, $invoiceDir, $invoiceName)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
        $this->snappyPdf = $snappyPdf;
        $this->templating = $templating;
        $this->invoiceDir = $invoiceDir;
        $this->invoiceName = $invoiceName;
    }

    public function addSubscription($subscriptionId, $billingData, $fileProvider, $domains = null, $userId = null)
    {
        $user = ($userId) ? $this->entityManager->getRepository('ApplicationSonataUserBundle:User')->find($userId) : $this->tokenStorage->getToken()->getUser();
        $order = new Order();

        $subscription = $this->entityManager->getRepository('AppBundle:Subscription')->find($subscriptionId);
        if ($this->getTotalValidDomains($subscription) === $subscription->getDomainAmount()) {
            foreach ($subscription->getDomains() as $domain) {
                if (!$domain->getDeleted()) {
                    $order->addDomain($domain);
                }
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
        $order->setFirstActive(false);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('order-success', 'success.order');
        $this->mailer->sendOrderInvoice($order, $this->generatePdfInvoice($order, $billingData, $fileProvider));

        return $order;
    }

    public function generatePdfInvoice($order, $billingData, $fileProvider)
    {
        $invoiceFilename = $this->invoiceName . '_' . sprintf('%06d', $order->getId()) . '.pdf';
        $invoicePath = $this->invoiceDir . $invoiceFilename;
        $invoiceBody = $this->templating->render('order/order_invoice_template.html.twig', array(
            'user' => $order->getUser(),
            'order' => $order,
            'billingData' => $billingData,
        ));
        $this->snappyPdf->generateFromHtml($invoiceBody, $invoicePath);

        $file = new UploadedFile($invoicePath, $invoiceFilename);
        $media = new Media();
        $media->setBinaryContent($file);
        $media->setName($invoiceFilename);
        $media->setProviderName('sonata.media.provider.file');
        $media->setContext('default');
        $media->setMediaType($media::INVOICE_TYPE);
        $order->setInvoice($media);
        $this->entityManager->persist($media);
        $this->entityManager->flush();
        $mediaPath = $this->invoiceDir . ".." . $fileProvider->generatePublicUrl($media, 'reference');

        $fs = new Filesystem();
        $fs->remove($invoicePath);

        return Swift_Attachment::fromPath($mediaPath)->setFilename($invoiceFilename);
    }

    public function getActiveCreditTotal($userId)
    {
        $orderRepository = $this->entityManager->getRepository('AppBundle:Order');
        $activeOrders = $orderRepository->findAllActiveUserOrders($userId);
        $bonusOrders = $orderRepository->findAllActiveBonusUserOrders($userId);
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

    public function getCreditTotal($userId)
    {
        $orderRepository = $this->entityManager->getRepository('AppBundle:Order');
        $activeOrders = $orderRepository->findAllUserOrders($userId);
        $bonusOrders = $orderRepository->findAllBonusUserOrders($userId);
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
        if ((null === $order) || ($order->getDeleted())) {

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
            $unlockedDocuments[$key]['subject'] = 'order.' . $document['usageType'];
            $unlockedDocuments[$key]['orderId'] = '';
            $unlockedDocuments[$key]['sign'] = '-';
        }

        return $unlockedDocuments;
    }

    public function getMediaObjects($unlockedDocuments)
    {
        $mediaObjects = [];
        foreach ($unlockedDocuments as $document) {
            if ($document['usageType'] !== CreditsUsage::TYPE_EXPIRED) {
                $mediaObjects[$document[$document['usageType'] . 'Id']] = ($document['mediaId']) ? $this->entityManager->getRepository('Application\Sonata\MediaBundle\Entity\Media')->find($document['mediaId']) : null;
            }
        }

        return $mediaObjects;
    }

    public function addInfoToHistoryOrders($allHistoryOrders)
    {
        foreach ($allHistoryOrders as $key => $order) {
            if ($order['name'] !== null) {
                $allHistoryOrders[$key]['subject'] = 'order.subscription';
                $allHistoryOrders[$key]['orderId'] = $order['id'];
            } else {
                $allHistoryOrders[$key]['subject'] = 'order.credits';
                $allHistoryOrders[$key]['orderId'] = '';
                $allHistoryOrders[$key]['name'] = 'order.credits-bonus';
            }
            $allHistoryOrders[$key]['sign'] = '+';
        }

        return $allHistoryOrders;
    }

    public function addInfoToExpiredCredits($allExpiredCredits)
    {
        foreach ($allExpiredCredits as $key => $credit) {
            $allExpiredCredits[$key]['subject'] = 'order.credits';
            $allExpiredCredits[$key]['name'] = 'order.credits-expired';
            $allExpiredCredits[$key]['sign'] = '-';
        }

        return $allExpiredCredits;
    }

    public function prepareCreditHistory($allHistoryOrders, $unlockedDocuments, $allExpiredCredits)
    {
        $creditHistoryItems = array_merge($allHistoryOrders, $unlockedDocuments, $allExpiredCredits);
        $expireDates = [];
        foreach ($creditHistoryItems as $key => $value) {
            $expireDates[$key] = $value['unlockDate'];
        }
        array_multisort($expireDates, SORT_DESC, $creditHistoryItems);

        return $creditHistoryItems;
    }

    public function getCreditHistory($userId)
    {

        $unlockedDocuments = $this->addInfoToUnlockedDocuments($this->entityManager->getRepository('AppBundle:CreditsUsage')->findAllUserDocuments($userId));
        $allHistoryOrders = $this->addInfoToHistoryOrders($this->entityManager->getRepository('AppBundle:Order')->findAllHistoryOrders($userId));
        $allExpiredCredits = $this->addInfoToExpiredCredits($this->entityManager->getRepository('AppBundle:CreditsUsage')->findAllUserExpiredCredit($userId));

        return $this->prepareCreditHistory($allHistoryOrders, $unlockedDocuments, $allExpiredCredits);
    }

    public function getTotalValidDomains($subscription)
    {
        $validDomains = 0;
        foreach ($subscription->getDomains() as $domain) {
            if (!$domain->getDeleted()) {
                $validDomains++;
            }
        }

        return $validDomains;
    }

    public function createDemoOrder($demoUser, $domains, $validDays, $defaultDemoCredits)
    {
        $order = new Order();

        $order->setUser($demoUser);
        $order->setCreditValue($defaultDemoCredits);
        foreach ($domains as $domain) {
            $order->addDomain($domain);
        }
        $order->setPrice('0');
        $order->setValabilityDays($validDays);
        $startDate = new \DateTime();
        $endDate = new \DateTime();
        $endDate->add(new \DateInterval('P' . $validDays . 'D'));
        $order->setStartDate($startDate);
        $order->setEndingDate($endDate);
        $order->setMentions($this->translator->trans('order.demo-account'));
        $order->setActive(true);
        $order->setFirstActive(true);
        $order->setDomainAmount(count($domains));
        $demoUser->setCreditsTotal($defaultDemoCredits);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

}