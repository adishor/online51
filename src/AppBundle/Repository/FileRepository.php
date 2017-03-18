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

    /**
     * @param $folderId
     * @return array
     */
    public function getFilesBySubdomain($subdomainId)
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('f, sd, fd')
            ->from('AppBundle:File', 'f')
            ->leftJoin('f.subdomain', 'sd')
            ->leftJoin('f.folder', 'fd')
            ->where('sd.id = :subdomain_id')
            ->andWhere('fd.id is NULL')
            ->setParameter('subdomain_id', $subdomainId)
            ->andWhere('sd.deleted = 0')
            ->andWhere('f.deleted = 0')
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

    /**
     * @param $folderId
     * @return array
     */
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

    /**
     * @param $folderId
     * @return array
     */
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