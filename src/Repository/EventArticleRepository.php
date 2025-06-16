<?php

namespace App\Repository;

use App\Entity\EventArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventArticle>
 */
class EventArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventArticle::class);
    }

//    /**
//     * @return EventArticle[] Returns an array of EventArticle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventArticle
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


        // public function findLastFive(): array
        // {
        //     return $this->createQueryBuilder('e')
        //                 ->orderBy('e.date', 'DESC')
        //                 ->setMaxResults(5)
        //                 ->getQuery()
        //                 ->getResult();
        // }

        public function findLast(int $limit): array
        {
            return $this->createQueryBuilder("e")
                        ->orderBy("e.date", "DESC")
                        ->setMaxResults($limit)
                        ->getQuery()
                        ->getResult();
        }

        public function findAllDesc(): array
        {
            return $this->createQueryBuilder("e")
                        ->orderBy("e.date", "DESC")
                        // ->setMaxResults($limit)
                        ->getQuery()
                        ->getResult();
        }
}
