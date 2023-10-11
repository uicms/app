<?php
namespace App\Repository;

use App\Entity\Event;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Event');
    }
    
	public function setRowData($row, $params=array())
	{
		$row = parent::setRowData($row, $params);
        
        if($row) {
            if($row->medias = $this->model('Media')->getAll(array('linked_to'=>'Event', 'linked_to_id'=>$row->getId()))) {
            	$row->_thumbnail = $row->medias[0]->_thumbnail;
                $row->_thumbnail_class = $row->medias[0]->getClass();
            }
            $row->topics = $this->model('Topic')->getAll(array('linked_to'=>'Event', 'linked_to_id'=>$row->getId()));
            $row->keywords = $this->model('Keyword')->getAll(array('linked_to'=>'Event', 'linked_to_id'=>$row->getId()));
            $row->venues = $this->model('Venue')->getAll(array('linked_to'=>'Event', 'linked_to_id'=>$row->getId()));
        }
        
		return $row;
	}
    
    function getQuery($params=array())
    {
        $query = parent::getQuery($params);

        if($this->mode == 'front') {

            /* Filters */
            if(isset($params['c']) && (array)$params['c']) {
                $condition = [];
                foreach($params['c'] as $i=>$c) {
                    $condition[] = "t.contributor=" . (int)$c;
                }
                $query->andWhere(implode(' AND ', $condition));
            }
            
            if (isset($params['d']) && $params['d']) {
                $date = explode(' - ', $params['d']);
                if(count($date) == 1) $date[1] = $date[0];
                $query->andWhere("t.date_begin <= '" . $date[1] . "' AND t.date_end >= '" . $date[0] . "'");
            }
            
            if(isset($params['tp']) && (array)$params['tp']) {
                $query->innerJoin('t.link_event_topic', 'ltp', 'WITH', 'ltp.event=t.id');
                $condition = [];
                foreach($params['tp'] as $i=>$tp) {
                    $condition[] = 'ltp.topic=' . (int)$tp;
                }
                $query->andWhere(implode(' AND ', $condition));
            }

            if(isset($params['k']) && (array)$params['k']) {
                $query->innerJoin('t.link_event_keyword', 'lk', 'WITH', 'lk.event=t.id');
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
}
