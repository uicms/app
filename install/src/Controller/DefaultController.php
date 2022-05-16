<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Uicms\App\Service\Model;

class DefaultController extends AbstractController
{
    public function index($page, Model $model)
    {
        $template = $page->getTemplate() ? $page->getTemplate() : 'index';
        
		return $this->render(
		            'app/tpl/default/' . $template . '.html.twig',
		            ['page'=>$page, 'medias'=>$model->get('Media')->getAll()]
		        );
    }
}