<?php
namespace App\Repository;

use App\Entity\ContributionType;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method ContributionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContributionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContributionType[]    findAll()
 * @method ContributionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributionTypeRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\ContributionType');
    }
}
