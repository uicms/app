<?php
namespace App\Repository;

use App\Entity\Contributor;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Contributor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contributor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contributor[]    findAll()
 * @method Contributor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributorRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Contributor');
    }

    public function setRowData($row, $params=array())
    {
        $row = parent::setRowData($row, $params);
        
        if($row && $this->mode == 'front') {
            $row->topics = $this->model('Topic')->getAll(array('linked_to'=>'Contributor', 'linked_to_id'=>$row->getId()));
        }
        
        return $row;
    }

    function getQuery($params=array())
    {
        $query = parent::getQuery($params);

        if($this->mode == 'front') {
            
            /* Topics */
            if(isset($params['tp']) && (array)$params['tp']) {
                $query->innerJoin('t.link_contributor_topic', 'ltp', 'WITH', 'ltp.contributor=t.id');
                $condition = [];
                foreach($params['tp'] as $i=>$tp) {
                    $condition[] = 'ltp.topic=' . (int)$tp;
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
                $search_fields = ['lastname', 'firstname', 'description'];
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
            }
        }
        
        
        return $query;
    }
}
