<?php
namespace App\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Uicms\App\Service\Model;
use App\Service\MenuHelper;

class IndexController extends AbstractController
{
    public function index(Model $model, Request $request, MenuHelper $menu_helper, $slug='', $action='', $locale='')
    {
        if($locale && !preg_match("'^[a-z]{2}$'", $locale)) {
            throw $this->createNotFoundException('This locale does not exist!');
        }
        
        $version = 'v1.2';
		$this->get('session')->set('theme_path', new PathPackage('themes/app', new StaticVersionStrategy($version)));
		$this->get('session')->set('js_path', new PathPackage('js', new StaticVersionStrategy($version)));
        
        # UI Config
		$ui_config = $this->getParameter('ui_config');
        $this->get('session')->set('ui_config', $ui_config);
        
		# Locale
		$session = $this->get('session');
		$session->set('locale', $locale);
        $request->setLocale($locale);
        
		# Current page
		if(!$slug) {
			$page = $model->get('Page')->getRow(['dir'=>0]);
		} else {
			$page = $model->get('Page')->getRow(['findby'=>['slug' => $slug]]);
		}
        while($page->getIsDir()) {
            if(!$page = $model->get('Page')->getRow(['dir'=>$page->getId()])) {
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
        $session->set('current_action', $action);
		
		# Menu
		$menu = $model->get('Page')->getAll(['dir'=>0, 'findby'=>['menu'=>'menu']]);
        foreach($menu as $i=>$menu_page) {
            $menu_page->helper_html = $menu_helper->get($menu_page);
            $menu_page->children = $model->get('Page')->getAll(['dir'=>$menu_page->getId()]);
        }
	    $session->set('menu', $menu);
        
        # Pages slugs
        if($authentication_page = $model->get('Page')->getRow(['findby'=>['controller'=>'authentication']])) {
            $this->get('session')->set('authentication_page_slug', $authentication_page->getSlug());
        }
        if($resources_page = $model->get('Page')->getRow(['findby'=>['controller'=>'resources']])) {
            $this->get('session')->set('resources_page_slug', $resources_page->getSlug());
        }
        if($events_page = $model->get('Page')->getRow(['findby'=>['controller'=>'events']])) {
            $this->get('session')->set('events_page_slug', $events_page->getSlug());
        }
        if($contributions_page = $model->get('Page')->getRow(['findby'=>['controller'=>'contributions']])) {
            $this->get('session')->set('contributions_page_slug', $contributions_page->getSlug());
        }
        if($directory_page = $model->get('Page')->getRow(['findby'=>['controller'=>'directory']])) {
            $this->get('session')->set('directory_page_slug', $directory_page->getSlug());
        }
        if($home_page = $model->get('Page')->getRow(['findby'=>['class'=>'home']])) {
            $this->get('session')->set('home_page_slug', $home_page->getSlug());
        }

        # Attributes
        $attributes = array_merge($request->query->all(), $request->request->all(), $request->attributes->all(), ['page' => $page]);
        
        # Params
        $req = $model->get('Param')->getAll();
        $params = [];
        foreach($req as $i=>$param) {
            $params[$param->getName()] = $param->getValue();
        }
        $session->set('params', $params);

        # Authentication
        $authenticated_controllers = [
            'contributions',
            'resources',
        ];
        
        if($authentication_page && in_array($page->getController(), $authenticated_controllers) && !$this->get('session')->get('contributor')) {
                return $this->redirectToRoute('app_page_action', array('slug'=>$this->get('session')->get('authentication_page_slug'), 'action'=>'index', 'locale'=>$this->get('session')->get('locale')));
        } else if($this->get('session')->get('contributor')) {
            $this->get('session')->set('contributor', $model->get('contributor')->getRowById($this->get('session')->get('contributor')->getId()));
        }
        
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