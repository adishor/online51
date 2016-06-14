<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdsRepository extends EntityRepository
{

    public function findAllByAreas($areas)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('ads')
          ->from('AppBundle:Ad', 'ads')
          ->join('ads.areas', 'ar')
          ->where('ar.slug IN (:areas)')
          ->setParameter('areas', $areas)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

}