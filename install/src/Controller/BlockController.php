<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Uicms\App\Service\Model;

class BlockController extends AbstractController
{
    public function index($page, Model $model)
    {
        return $this->render(
            'app/tpl/block/index.html.twig',
            [
                'page'=>$page,
                'blocks'=> $model->get('Block')->getAll(array('linked_to'=>'Page', 'linked_to_id'=>$page->getId()))
            ]
        );
    }
}