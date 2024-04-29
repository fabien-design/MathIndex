<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Skill>
 *
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    public function findByName($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }

    public function findSkillsByCourse(?Course $course): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.course', 'c')
            ->where('c.name = :courseName')
            ->setParameter('courseName', $course->getName())
            ->getQuery()
            ->getResult();
    }
}
