<?php

namespace App\Repository;

use App\Entity\ContributorGradeTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContributorGradeTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContributorGradeTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContributorGradeTranslation[]    findAll()
 * @method ContributorGradeTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributorGradeTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContributorGradeTranslation::class);
    }

    // /**
    //  * @return ContributorGradeTranslation[] Returns an array of ContributorGradeTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContributorGradeTranslation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
