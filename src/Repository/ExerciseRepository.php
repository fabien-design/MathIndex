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

    public function findExercisesByResearch(?Thematic $thematic, ?Classroom $classroom, ?array $keywords)
    {
        $qb = $this->createQueryBuilder('ex');

            // Cas où tous les champs sont remplis
            $qb->join('ex.thematic', 't')
                ->join('ex.classroom', 'c');
                if ($thematic != null){
                    $qb->where('t.name = :thematicName')
                        ->setParameter('thematicName', $thematic->getName());
                }
               if ($classroom != null){
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
