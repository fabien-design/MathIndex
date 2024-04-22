<?php

namespace App\Repository;

use App\Entity\Classroom;
use App\Entity\Exercise;
use App\Entity\Thematic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 *
 * @method Exercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercise[]    findAll()
 * @method Exercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    public function findExercise($searchTerm): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->innerJoin('c.course', 'course')
        ->innerJoin('c.classroom', 'classroom')
        ->innerJoin('c.thematic', 'thematic')
        // ->innerJoin('c.origin', 'origin')
        ->innerJoin('c.createdBy', 'createdBy')
        ->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('c.name', ':search'),
                $qb->expr()->like('c.chapter', ':search'),
                $qb->expr()->like('c.keywords', ':search'),
                $qb->expr()->like('c.originName', ':search'),
                $qb->expr()->like('c.proposedByType', ':search'),
                $qb->expr()->like('c.proposedByFirstName', ':search'),
                $qb->expr()->like('c.proposedByLasName', ':search'),
                $qb->expr()->like('c.createdAt', ':search'),
                $qb->expr()->like('course.name', ':search'),
                $qb->expr()->like('classroom.name', ':search'),
                $qb->expr()->like('thematic.name', ':search'),
                // $qb->expr()->like('origin.name', ':search'),
            )
        )
        ->orderBy("c.createdAt", "desc")
        ->setParameter('search', '%'.$searchTerm.'%');

        return $qb->getQuery()->getResult();
    }

    public function findExercisesByResearch(?Thematic $thematic, ?Classroom $classroom, ?array $keywords)
    {
        $qb = $this->createQueryBuilder('ex');

        // Cas où tous les champs sont remplis
        $qb->join('ex.thematic', 't')
            ->join('ex.classroom', 'c');
        if (null != $thematic) {
            $qb->where('t.name = :thematicName')
                ->setParameter('thematicName', $thematic->getName());
        }
        if (null != $classroom) {
            $qb->andWhere('c.name = :className')
                ->setParameter('className', $classroom->getName());
        }
        if (!empty($keywords)) {
            // Cas où seulement les mots-clés sont remplis
            $andExpr = null;

            foreach ($keywords as $keyword) {
                $likeExpr = $qb->expr()->like('ex.keywords', $qb->expr()->literal('%'.$keyword.'%'));

                if (null === $andExpr) {
                    $andExpr = $likeExpr;
                } else {
                    $andExpr = $qb->expr()->andX($andExpr, $likeExpr);
                }
            }

            if (null !== $andExpr) {
                $qb->andWhere($andExpr);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
