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

        $yesterday = new \DateTime();
        $yesterday->modify('-1 day');

        $file = $event->getFile();
        $creditValue = $file->getCreditValue();

        if ($user->getLastCreditUpdate() < $yesterday) {

            $orderRepository = $this->entityManager->getRepository('AppBundle:Order');
            $creditsUsageRepository = $this->entityManager->getRepository('AppBundle:CreditsUsage');

            $oldestValidOrder = $orderRepository->getOldestValidOrderByUserId($userId);
            $validNowCredits = $orderRepository->findValidUserCredits($userId);

            $usedCreditsFromDate = $creditsUsageRepository->getTotalUsedCreditsFromDate($userId, $oldestValidOrder->getStartDate());

            $updatedCredits = $validNowCredits - $usedCreditsFromDate;

            $user->setCreditsTotal($updatedCredits);
            $user->setLastCreditUpdate(new \DateTime());
        } else {
            $usedCredits = $user->getCreditsTotal() - $creditValue;
            $user->setCreditsTotal($usedCredits);
        }

        $this->entityManager->flush();
    }

}