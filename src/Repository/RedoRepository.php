<?php

namespace App\Repository;

use App\Entity\Redo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Redo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Redo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Redo[]    findAll()
 * @method Redo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redo::class);
    }

    public function findPending($redo)
    {
        return $this->createQueryBuilder('r')
            ->where('r.batch = :batch')
            ->setParameter('batch', $redo)
            ->groupBy('r.mappingId')
            ->getQuery()
            ->getResult()
        ;
    }



    public function findForPeriodChecker($batch)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.batch = :batch')
            ->setParameter('batch', $batch)
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
