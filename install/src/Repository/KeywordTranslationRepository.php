<?php

namespace App\Repository;

use App\Entity\KeywordTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KeywordTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method KeywordTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method KeywordTranslation[]    findAll()
 * @method KeywordTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeywordTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KeywordTranslation::class);
    }

    // /**
    //  * @return KeywordTranslation[] Returns an array of KeywordTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KeywordTranslation
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
