<?php

namespace App\Repository;

use App\Entity\Origin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Origin>
 *
 * @method Origin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Origin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Origin[]    findAll()
 * @method Origin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OriginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Origin::class);
    }

    public function findByName($value): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }
}
