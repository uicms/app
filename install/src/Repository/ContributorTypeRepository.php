<?php
namespace App\Repository;

use App\Entity\ContributorType;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method ContributorType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContributorType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContributorType[]    findAll()
 * @method ContributorType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributorTypeRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\ContributorType');
    }
}
