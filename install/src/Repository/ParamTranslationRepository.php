<?php

namespace App\Repository;

use App\Entity\ParamTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParamTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParamTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParamTranslation[]    findAll()
 * @method ParamTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParamTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParamTranslation::class);
    }

    // /**
    //  * @return ParamTranslation[] Returns an array of ParamTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParamTranslation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
