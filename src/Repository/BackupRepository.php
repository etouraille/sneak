<?php

namespace App\Repository;

use App\Entity\Backup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Backup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Backup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Backup[]    findAll()
 * @method Backup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backup::class);
    }

    public function getOldest()
    {
        $oldestBackup =  $this->createQueryBuilder('b')
            ->orderBy('b.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;

        return $this->createQueryBuilder('b')
            ->where('b.date = :date')
            ->orderBy('b.date', 'ASC')
            ->setParameter('date', $oldestBackup->getDate())
            ->getQuery()
            ->getResult()
        ;
    }
}
