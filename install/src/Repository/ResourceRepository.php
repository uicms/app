<?php
namespace App\Repository;

use App\Entity\Resource;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Resource');
    }
    
	public function setRowData($row, $params=array())
	{
		$row = parent::setRowData($row, $params);
        
        if($row) {
            if($row->medias = $this->model('Media')->getAll(array('linked_to'=>'Resource', 'linked_to_id'=>$row->getId()))) {
            	$row->_thumbnail = $row->medias[0]->_thumbnail;
                $row->_thumbnail_class = $row->medias[0]->getClass();
            }
        }
        
		return $row;
	}

    function getQuery($params=array())
    {
        $query = parent::getQuery($params);

        if($this->mode == 'front') {
            
            /*if(isset($params['linked_to_topics']) && (array)$params['linked_to_topics']) {
                $query->innerJoin('t.link_resource_topic', 'ltp', 'WITH', 'ltp.resource=t.id');
                $condition = [];
                foreach($params['linked_to_topics'] as $i=>$topic) {
                    $condition[] = 'ltp.topic=' . $topic->getId();
                }
                $query->andWhere(implode(' OR ', $condition));        
            }*/

            /* Filters */
            if(isset($params['tp']) && (array)$params['tp']) {
                $query->innerJoin('t.link_resource_topic', 'ltp', 'WITH', 'ltp.resource=t.id');
                $condition = [];
                foreach($params['tp'] as $i=>$tp) {
                    $condition[] = 'ltp.topic=' . (int)$tp;
                }
                $query->andWhere(implode(' AND ', $condition));             
            }

            if(isset($params['k']) && (array)$params['k']) {
                $query->innerJoin('t.link_resource_keyword', 'lk', 'WITH', 'lk.resource=t.id');
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
