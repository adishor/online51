<?php
/**
 * Created by PhpStorm.
 * User: adriancalugar
 * Date: 12/02/2017
 * Time: 12:09
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Document;
use AppBundle\Entity\Video;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class FileRepository extends EntityRepository
{

    public function findUserFileBySubdomain($userId, $subdomainId)
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('f', 'sd', 'cu')
            ->from('AppBundle:File ', 'f')
            ->join('f.subdomain', 'sd')
            ->leftJoin('f.creditsUsage', 'cu', 'WITH', 'cu.user = :userId AND cu.expireDate > :now')
            ->setParameter('userId', $userId)
            ->setParameter('now', new \DateTime)
        ;

        $query = $queryBuilder->getQuery();

        $results = $query->getResult();
        $returnArray = array();

        foreach ($results as $item) {
            if ($item instanceof Video) {
                $returnArray['video'][] = $item;
            } elseif ($item instanceof Document) {
                $returnArray['document'][] = $item;
            } else {
                $returnArray['formular'][] = $item;
            }
        }

        return $returnArray;
    }

    public function getFilesByFolder($folderId)
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('f')
            ->from('AppBundle:File', 'f')
            ->leftJoin('f.folder', 'fd')
            ->where('fd.id = :folder_id')
            ->setParameter('folder_id', $folderId)
            ->andWhere('fd.deleted = 0')
            ->andWhere('f.deleted = 0')
        ;

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function getFilesByFolderOrganizedByType($folderId)
    {
        $files = $this->getFilesByFolder($folderId);

        $response = [
            'video' => [],
            'formular' => [],
            'document' => [],
        ];

        foreach ($files as $file) {
            if ($file instanceof Video) {
                $response['video'][] = $file;
            } elseif ($file instanceof Document) {
                $response['document'][] = $file;
            } else {
                $response['formular'][] = $file;
            }
        }

        return $response;
    }
}