<?php

namespace App\Repository;

use App\Entity\BlockTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlockTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlockTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlockTranslation[]    findAll()
 * @method BlockTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockTranslation::class);
    }

    // /**
    //  * @return BlockTranslation[] Returns an array of BlockTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlockTranslation
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
