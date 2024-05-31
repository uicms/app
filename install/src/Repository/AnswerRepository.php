<?php
namespace App\Repository;

use App\Entity\Answer;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters, SessionInterface $session)
    {
        $this->session = $session;
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Answer');
    }

    function getQuery($params=array())
    {
        $query = parent::getQuery($params);

        if($this->mode == 'front') {
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
            $row->count_likes = $this->model('Selection')->count(array('findby'=>['type'=>'like', 'answer'=>$row]));
            $row->is_liked = $this->model('Selection')->getRow(['findby'=>['type'=>'like', 'answer'=>$row, 'contributor'=>$this->session->get('contributor')]]) ? true : false;
        }
        
        return $row;
    }
}
