<?php

namespace App\Repository;

use App\Entity\TopicTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TopicTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicTranslation[]    findAll()
 * @method TopicTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicTranslation::class);
    }

    // /**
    //  * @return TopicTranslation[] Returns an array of TopicTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TopicTranslation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
