<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Uicms\App\Service\Model;

class BlocksController extends AbstractController
{
    public function index($page, Model $model)
    {
        $blocks = $model->get('Block')->getAll(['linked_to'=>'Page', 'linked_to_id'=>$page->getId()]);
        
        foreach($blocks as $i=>$block) {
            # Collection
            if($block->getTemplate() == 'collection' && $block->getBlockCollection()) {

                $block->items = $model->get($block->getBlockCollection()->getEntity())->getAll(['linked_to'=>'BlockCollection', 'linked_to_id'=>$block->getBlockCollection()->getId()]);
            }
        }

        return $this->render(
            'app/tpl/blocks/index.html.twig',
            [
                'page'=>$page,
                'blocks'=>$blocks,
            ]
        );
    }
}