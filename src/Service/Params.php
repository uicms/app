<?php

namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;


class Params
{
    var $params = array();
    
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->ui_config = $parameters->get('ui_config');
    }

    public function get($page_slug, $request)
    {
        # Is it in app or admin ?
		$module = strpos($request->attributes->get('_route'), 'admin') === 0 ? 'admin' : 'app';
        
        # Global defaults
        foreach($this->ui_config[$module]['vars'] as $key => $value) {
            $this->params[$key] = $value;
        }
        
        # Page defaults
        if(isset($this->ui_config[$module]['pages'][$page_slug]['params']) && is_array($this->ui_config[$module]['pages'][$page_slug]['params'])) {
            foreach($this->ui_config[$module]['pages'][$page_slug]['params'] as $key => $value) {
                $this->params[$key] = $value;
            }
        }
        
        # Get values from session if not provided in url
        $params_in_session = $request->getSession()->get($page_slug);
        foreach($this->params as $key => $value) {
            if($request->attributes->has($key)) {
                $this->params[$key] = $request->attributes->get($key);
                $params_in_session[$key] = $this->params[$key];
            } else if(!$request->attributes->has($key) && isset($params_in_session[$key])) {
                $this->params[$key] = $params_in_session[$key];
            }
        }
        
        $request->getSession()->set($page_slug, $params_in_session);
        
        return $this->params;
    }
    
    public function unset($namespace, $param, $request)
    {
        $params_in_session = $request->getSession()->get($namespace);
        if(isset($params_in_session[$param])) {
            unset($params_in_session[$param]);
            $request->getSession()->set($namespace, $params_in_session);
            return true;
        }
        return false;
    }
}