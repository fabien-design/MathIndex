<?php

namespace App\Repository;

use App\Entity\Thematic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Thematic>
 *
 * @method Thematic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematic[]    findAll()
 * @method Thematic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematic::class);
    }

    public function findByName($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }
}
