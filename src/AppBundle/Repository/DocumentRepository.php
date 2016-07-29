<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DocumentRepository extends EntityRepository
{

    public function findAllZeroCreditValueDocuments($domainId = null, $subdomainId = null)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('d.id as id, d.name, m.id as mid, '
            . 'sd.name as subDomain, dom.name as domain')
          ->from('AppBundle:Document', 'd')
          ->join('Application\Sonata\MediaBundle\Entity\Media', 'm', 'WITH', 'd.media = m')
          ->join('AppBundle:SubDomain', 'sd', 'WITH', 'd.subdomain = sd')
          ->join('AppBundle:Domain', 'dom', 'WITH', 'sd.domain = dom')
          ->where('d.creditValue = 0')
          ->andWhere('d.deleted = FALSE')
          ->andWhere('m.deleted = FALSE')
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
          ->addOrderBy('d.id', 'DESC');

        $query = $queryBuilder->getQuery();

        return $query->getArrayResult();
    }

    public function findZeroCreditValueDocumentForMedia($mediaId)
    {
        $queryBuilder = $this->getEntityManager()
          ->createQueryBuilder()
          ->select('d')
          ->from('AppBundle:Document', 'd')
          ->where('d.media = :media')
          ->setParameter('media', $mediaId)
          ->andWhere('d.creditValue = 0');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

}