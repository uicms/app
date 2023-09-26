<?php
namespace App\Repository;

use App\Entity\Answer;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Answer');
    }

    function getQuery($params=array())
    {
        $query = parent::getQuery($params);

        if($this->mode == 'front') {

            /* Filters */
            if(isset($params['has_not_parent']) && $params['has_not_parent']) {
                $query->andWhere('t.parent_answer IS NULL OR t.parent_answer=0');  
            }
        }

        return $query;
    }

    public function setRowData($row, $params=array())
    {
        $row = parent::setRowData($row, $params);
        
        if($row && $this->mode == 'front') {
            $row->count_likes = $this->model('LikeAnswer')->count(array('findby'=>['answer'=>$row->getId()]));
            $row->sub_answers = $this->model('Answer')->mode('front')->getAll(['findby'=>['parent_answer'=>$row]]);
        }
        
        return $row;
    }
}
