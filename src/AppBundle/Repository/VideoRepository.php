<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VideoRepository extends EntityRepository
{

    public function findAllZeroCreditValueVideos($domainId = null, $subdomainId = null)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('v.id as id, v.name, m.id as mid, '
            . 'sd.name as subDomain, dom.name as domain')
          ->from('AppBundle:Video', 'v')
          ->leftJoin('Application\Sonata\MediaBundle\Entity\Media', 'm', 'WITH', 'v.media = m')
          ->join('AppBundle:SubDomain', 'sd', 'WITH', 'v.subdomain = sd')
          ->join('AppBundle:Domain', 'dom', 'WITH', 'sd.domain = dom')
          ->where('v.creditValue = 0')
          ->andWhere('v.deleted = FALSE')
          ->andWhere('sd.deleted = FALSE')
          ->andWhere('dom.deleted = FALSE');
        if (null !== $domainId) {
            $queryBuilder->andWhere('sd.domain = :domain')
              ->setParameter('domain', $domainId);
        }
        if (null !== $subdomainId) {
            $queryBuilder->andWhere('sd.id = :subdomainId')
              ->setParameter('subdomainId', $subdomainId);
        }
        $queryBuilder->addOrderBy('dom.id')
          ->addOrderBy('sd.id')
          ->addOrderBy('v.id', 'DESC');

        $query = $queryBuilder->getQuery();

        return $query->getArrayResult();
    }

    public function findZeroCreditValueVideoForMedia($mediaId)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('v')
          ->from('AppBundle:Video', 'v')
          ->where('v.media = :media')
          ->setParameter('media', $mediaId)
          ->andWhere('v.creditValue = 0');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

}