<?php
namespace App\Repository;

use App\Entity\LinkEventContributor;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method LinkEventContributor|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkEventContributor|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkEventContributor[]    findAll()
 * @method LinkEventContributor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkEventContributorRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\LinkEventContributor');
    }
}
