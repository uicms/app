<?php
namespace App\Repository;

use App\Entity\LinkResourceKeyword;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method LinkResourceKeyword|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkResourceKeyword|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkResourceKeyword[]    findAll()
 * @method LinkResourceKeyword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkResourceKeywordRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\LinkResourceKeyword');
    }
}
