<?php
namespace App\Repository;

use App\Entity\LinkContributorTopic;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method LinkContributorTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkContributorTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkContributorTopic[]    findAll()
 * @method LinkContributorTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkContributorTopicRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\LinkContributorTopic');
    }
}
