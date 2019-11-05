<?php

namespace App\Repository;

use App\Entity\SizePrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SizePrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SizePrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SizePrice[]    findAll()
 * @method SizePrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SizePriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SizePrice::class);
    }

    // /**
    //  * @return SizePrice[] Returns an array of SizePrice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SizePrice
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
