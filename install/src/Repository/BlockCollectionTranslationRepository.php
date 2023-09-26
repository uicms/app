<?php

namespace App\Repository;

use App\Entity\BlockCollectionTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlockCollectionTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlockCollectionTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlockCollectionTranslation[]    findAll()
 * @method BlockCollectionTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockCollectionTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockCollectionTranslation::class);
    }

    // /**
    //  * @return BlockCollectionTranslation[] Returns an array of BlockCollectionTranslation objects
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
    public function findOneBySomeField($value): ?BlockCollectionTranslation
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
