<?php
namespace App\Repository;

use App\Entity\Contribution;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Contribution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contribution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contribution[]    findAll()
 * @method Contribution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributionRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters, SessionInterface $session)
    {
        $this->session = $session;
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Contribution');
    }

    function getQuery($params=array())
    {
        $query = parent::getQuery($params);
        

        if($this->mode == 'front') {

            /* Filters */
            if($contributor = $this->session->get('contributor')) {
                if(isset($params['sl']) && $params['sl']) {
                    $query->innerJoin('t.selection', 'sl', 'WITH', "sl.contribution=t.id");
                    $query->andWhere("sl.type='selection' AND sl.contributor=" . $contributor->getId());   
                }
                if(isset($params['mc']) && $params['mc']) {   
                    $query->andWhere('t.contributor=' . $contributor->getId());
                }
            }
            
            if(isset($params['c']) && (array)$params['c']) {
                $condition = [];
                foreach($params['c'] as $i=>$c) {
                    $condition[] = "t.contributor=" . (int)$c;
                }
                $query->andWhere(implode(' AND ', $condition));
            }
            
            if(isset($params['t']) && (array)$params['t']) {
                $condition = [];
                foreach($params['t'] as $i=>$t) {
                    $condition[] = "t.contribution_type=" . (int)$t;
                }
                $query->andWhere(implode(' AND ', $condition));
            }

            if(isset($params['st']) && (array)$params['st']) {
                $condition = [];
                foreach($params['st'] as $i=>$st) {
                    $condition[] = "t.contribution_status=" . (int)$st;
                }
                $query->andWhere(implode(' AND ', $condition));
            }

            if(isset($params['o']) && (array)$params['o']) {
                $condition = [];
                foreach($params['o'] as $i=>$o) {
                    $condition[] = "t.contribution_object=" . (int)$o;
                }
                $query->andWhere(implode(' AND ', $condition));
            }

            if(isset($params['tp']) && (array)$params['tp']) {
                $query->innerJoin('t.link_contribution_topic', 'ltp', 'WITH', 'ltp.contribution=t.id');
                $condition = [];
                foreach($params['tp'] as $i=>$tp) {
                    $condition[] = 'ltp.topic=' . (int)$tp;
                }
                $query->andWhere(implode(' AND ', $condition));
            }

            if(isset($params['k']) && (array)$params['k']) {
                $query->innerJoin('t.link_contribution_keyword', 'lk', 'WITH', 'lk.contribution=t.id');
                $condition = [];
                foreach($params['k'] as $i=>$k) {
                    $condition[] = 'lk.keyword=' . (int)$k;
                }
                $query->andWhere(implode(' AND ', $condition));
            }


            /* Search */
            if (isset($params['s']) && $params['s']) {

                $search_string = trim(preg_replace("#[^\w0-9]+#u", " ", $params['s']));
                preg_match_all("'([^ ]*?\".*?\"|[^ ]+) *'", addslashes($search_string), $preg);
                $search_terms = [];
                foreach($preg[1] as $i=>$term) {
                    if(strlen($term) > 2) {
                        $search_terms[] = $term;
                    }
                }
                $where = [];

                # Search in main table
                $search_fields = ['name','description'];
                foreach ($search_fields as $field) {
                    $tmp = '(';
                    foreach ($search_terms as $i=>$term) {
                        if($i) $tmp .= " AND ";
                        if ($this->isFieldTranslatable($field)) {
                            $tmp .= "i.$field LIKE '%$term%'";
                        } else {
                            $tmp .= "t.$field LIKE '%$term%'";
                        }
                    }
                    $where[] = $tmp . ')';
                }                

                $query->andWhere(implode (' OR ', $where));
                #dd($query->getDql());
            }
        }
        
        
        return $query;
    }
    
	public function setRowData($row, $params=array())
	{
		$row = parent::setRowData($row, $params);
        
        if($row && $this->mode == 'front') {
            
            # Linked data
            $row->topics = $this->model('Topic')->getAll(array('linked_to'=>'Contribution', 'linked_to_id'=>$row->getId()));
            $row->keywords = $this->model('Keyword')->getAll(array('linked_to'=>'Contribution', 'linked_to_id'=>$row->getId()));
            
            # Selection & counting
            $row->count_answers = $this->model('Answer')->count(array('findby'=>['contribution'=>$row->getId()]));
            $row->count_likes = $this->model('Selection')->count(array('findby'=>['type'=>'like', 'contribution'=>$row]));
            $row->is_selected = $this->model('Selection')->getRow(['findby'=>['type'=>'selection', 'contribution'=>$row, 'contributor'=>$this->session->get('contributor')]]) ? true : false;
            $row->is_liked = $this->model('Selection')->getRow(['findby'=>['type'=>'like', 'contribution'=>$row, 'contributor'=>$this->session->get('contributor')]]) ? true : false;

            # Status depending on date
            $today = new \DateTime();
            if($row->getDateEnd() && $today->format('Y-m-d') > $row->getDateEnd()->format('Y-m-d')) {
                $closed_status = $this->model('ContributionStatus')->getRow(['findby'=>['slug'=>'closed']]);
                $row->setContributionStatus($closed_status);
                $this->model('Contribution')->persist($contribution);
            }
        }
        
		return $row;
	}
}
