<?php
namespace App\Repository;

use App\Entity\Venue;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Venue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Venue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Venue[]    findAll()
 * @method Venue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenueRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Venue');
    }
}
