<?php

namespace App\Repository;

use App\Entity\Mapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Mapping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mapping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mapping[]    findAll()
 * @method Mapping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MappingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mapping::class);
    }

    public function count(array $criteria = []) {

        $qb = $this->createQueryBuilder('m');
        $qb->select('COUNT(m.id)');

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;

    }


    public function page($page = 1 , $perPage = 6 ): ?array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->setFirstResult(($page -1 ) * $perPage )
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult()
        ;
    }

}