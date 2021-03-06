<?php
namespace App\Repository;

use App\Entity\Block;
use Uicms\App\Repository\BaseRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @method Block|null find($id, $lockMode = null, $lockVersion = null)
 * @method Block|null findOneBy(array $criteria, array $orderBy = null)
 * @method Block[]    findAll()
 * @method Block[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockRepository extends BaseRepository
{
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $password_encoder, ParameterBagInterface $parameters)
    {
        parent::__construct($security, $registry, $password_encoder, $parameters, 'App\Entity\Block');
    }
    
    function setRowData($row, $params=array()) {
        $row = parent::setRowData($row, $params);
        
        if($this->mode == 'front') {
            $row->medias = $this->model('Media')->getAll(array('linked_to'=>'Block', 'linked_to_id'=>$row->getId()));
            if($row->getType()=='folder' && $row->getParams()) {
                $row->pages = $this->model('Page')->getAll(array('dir'=>$row->getParams()));
                foreach($row->pages as $i=>$page) {
                    $page->medias = $this->model('Media')->getAll(array('linked_to'=>'Page', 'linked_to_id'=>$page->getId()));
                    $page->cover = $page->medias ? $page->medias[0]->getFile() : '';
                }
            }
            $row->cover = $row->medias ? $row->medias[0]->getFile() : '';
        }
        
        return $row;
    }
}
