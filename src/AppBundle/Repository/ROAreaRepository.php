<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ROAreaRepository extends EntityRepository
{

    public function findAllByCounty($countySlug)
    {
        $query = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('a')
          ->from('AppBundle:ROArea', 'a')
          ->join('a.counties', 'c')
          ->where('c.slug = :countySlug')
          ->setParameter('countySlug', $countySlug)
          ->getQuery();

        return $query->getResult();
    }

}