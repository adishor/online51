<?php

/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 10/02/2017
 * Time: 17:55
 */
namespace AppBundle\EventListener;

use AppBundle\EventListener\Event\CreditUsedEvent;
use AppBundle\Service\UserService;
use Doctrine\ORM\EntityManager;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreditUsageListener implements EventSubscriberInterface
{
    private $userService;
    private $entityManager;
    private $translator;

    public function __construct(UserService $userService, EntityManager $entityManager, $translator)
    {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'app.event.credit_used' => 'handler'
        );
    }

    public function handler(CreditUsedEvent $event)
    {
        $userId = $this->userService->getLoggedUserId();
        $user = $this->entityManager->find('ApplicationSonataUserBundle:User', $userId);
        $userCredits = $user->getCreditsTotal();

        $orderRepository = $this->entityManager->getRepository('AppBundle:Order');

        $validBeforeCredits = $orderRepository->findValidUserCredits($userId, $user->getLastCreditUpdate());

        $validNowCredits = $orderRepository->findValidUserCredits($userId);

        if (null !== $userCredits) {
            $updatedCredits = min($userCredits, $validBeforeCredits) + ($validNowCredits - $validBeforeCredits);
            $user->setCreditsTotal($updatedCredits);
            $user->setLastCreditUpdate(new \DateTime());
            if ($userCredits > $updatedCredits) {
                $this->createExpiredCreditUsage($user, $userCredits - $updatedCredits);
            }
        }
        $this->entityManager->flush();
    }

    private function createExpiredCreditUsage($user, $credit, $withFlush = false)
    {
        $creditsUsage = new CreditsUsage();
        $creditsUsage->setUser($user);
        $user->setLastCreditUpdate(new \DateTime());
        $creditsUsage->setMentions($this->translator->trans('credit-usage.expired'));
        $expireDate = new \DateTime();
        $creditsUsage->setExpireDate($expireDate);
        $creditsUsage->setCredit($credit);
        $creditsUsage->setUsageType(CreditsUsage::TYPE_EXPIRED);
        $this->entityManager->persist($creditsUsage);

        if ($withFlush) {
            $this->entityManager->flush();
        }
    }
}