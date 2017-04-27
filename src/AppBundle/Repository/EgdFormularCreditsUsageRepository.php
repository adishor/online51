<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 04/03/2017
 * Time: 14:00
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\CreditsUsage;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EgdFormularCreditsUsageRepository extends EntityRepository
{


    /**
     * @param $userId
     * @return array
     * @internal param null $mediaId
     */
    public function findAllUserFormularDocuments($userId)
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p.company, f.name, f.slug as fslug, f.discountedCreditValue, m.id as mid, '
                . 'cu.id as cuid, fc.formConfig, fc.formHash, fc.isFormConfigFinished, fc.year, fc.code, fc.step, cu.expireDate as date, cu.title, '
                . 'sd.name as subDomain, dom.name as domain')
            ->from('AppBundle:EgdFormularCreditsUsage', 'cu')
            ->join('cu.formular', 'f')
            ->leftJoin('cu.formularConfig', 'fc')
            ->join('cu.user', 'u')
            ->join('u.profile', 'p')
            ->join('f.subdomain', 'sd')
            ->join('sd.domain', 'dom')
            ->leftJoin('cu.media', 'm')
            ->where('cu.user = :user')
            ->setParameter('user', $userId)
            ->andWhere('cu.deleted = FALSE')
            ->addOrderBy('cu.expireDate', 'DESC')
            ->addOrderBy('cu.createdAt', 'DESC');

        $query = $queryBuilder->getQuery();

        return $query->getArrayResult();
    }


}