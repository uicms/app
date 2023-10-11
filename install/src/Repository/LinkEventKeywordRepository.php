<?php
namespace App\Repository;

use App\Entity\LinkEventKeyword;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method LinkEventKeyword|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkEventKeyword|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkEventKeyword[]    findAll()
 * @method LinkEventKeyword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkEventKeywordRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\LinkEventKeyword');
    }
}
