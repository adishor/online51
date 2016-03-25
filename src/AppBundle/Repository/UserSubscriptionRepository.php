<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserSubscriptionRepository extends EntityRepository
{
    public function getLastRecord()
    {
        return $this->createQueryBuilder('us')
                    ->orderBy('us.id', 'DESC')
                    ->getQuery()
                    ->getResult();
    }
}

