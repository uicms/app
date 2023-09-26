<?php
namespace App\Repository;

use App\Entity\LikeAnswer;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method LikeAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeAnswer[]    findAll()
 * @method LikeAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeAnswerRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\LikeAnswer');
    }
}
