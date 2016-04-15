<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends EntityRepository
{

    public function findValidUserDomain($userId, $domainId)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('DISTINCT(d.id) as id')
          ->from('AppBundle:Order', 'o')
          ->join('o.domains', 'd')
          ->where('o.active = TRUE')
          ->andWhere('o.user = :user')
          ->setParameter('user', $userId)
          ->andWhere('d.id = :domain')
          ->setParameter('domain', $domainId)
          ->andWhere('o.endingDate > :now')
          ->setParameter('now', new \DateTime);

        $query = $queryBuilder->getQuery();

        return $query->getArrayResult();
    }

    public function findValidUserCredits($userId)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('SUM(o.credits)')
          ->from('AppBundle:Order', 'o')
          ->where('o.active = TRUE')
          ->andWhere('o.user = :user')
          ->setParameter('user', $userId)
          ->andWhere('o.endingDate > :now')
          ->setParameter('now', new \DateTime);

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResultResult();
    }

}