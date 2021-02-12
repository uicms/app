<?php
namespace App\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Uicms\App\Service\Model;

class IndexController extends AbstractController
{
    public function index(Model $model, Request $request, $slug='', $action='', $locale='')
    {
        # UI Config
		$ui_config = $this->getParameter('ui_config');
        $this->get('session')->set('ui_config', $ui_config);
        
		# Locale
		$session = $this->get('session');
		$session->set('locale', $locale);
        $request->setLocale($locale);
        
		# Current page
		$repo = $model->get('App\Entity\Page');
		if(!$slug) {
			$page = $repo->getRow(array('dir'=>0));
		} else {
			$page = $repo->getRow(array('findby'=>array('slug' => $slug)));
		}
        while($page->getIsDir()) {
            if(!$page = $repo->getRow(array('dir'=>$page->getId()))) {
                break;
            }
        }
		if(!$page) {
			throw $this->createNotFoundException('No data found for page '.$slug);
		}
		
		# Current action
		if(!$action) {
			$action = $page->getAction() ? $page->getAction() : 'index';
		}
		
		# Menu
		$menu = $repo->getAll(array('dir'=>0, 'findby'=>array('menu'=>'menu')));
        foreach($menu as $i=>$menu_page) {
            if($menu_page->getIsdir()) {
                $menu_page->children = $repo->getAll(array('dir'=>$menu_page->getId()));
            } else {
                $menu_page->children = array();
            }
        }
	    $session->set('menu', $menu);
        
        # Attributes
        $attributes = array_merge($request->query->all(), $request->request->all(), $request->attributes->all(), array('page' => $page));
        
		# Forward to the correct controller
        return $this->forward("App\\Controller\\" . ucfirst($page->getController()) . "Controller::" . $action, $attributes);
	}
    
    public function error(FlattenException $exception, Model $model, Request $request, $slug='', $action='', $locale='')
    {
        return $this->render(
            'app/tpl/error/error.html.twig',
            ['exception'=>$exception]
        );
	}
}