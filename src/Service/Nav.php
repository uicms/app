<?php

namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Nav
{	
	public function __construct(RequestStack $requestStack)
    {
		$request = $requestStack->getCurrentRequest();
		$this->session = $request->getSession();
    }
	
    
    /* History */
    public function addRoute($route)
    {
        $history = $this->session->get('history') ? $this->session->get('history') : array();
        $total = count($history);
       
        if(($total && $history[$total-1] != $route) || !$total) {
            $history[] =  $route;
            $this->session->set('history', $history);
        }

		return $this;
    }
    
    public function getAllRoutes()
    {
        return $this->session->get('history') ? $this->session->get('history') : array();
    }
    
    public function getPreviousRoute()
    {
        $history = $this->getAllRoutes();
        if(isset($history[count($history)-2])) {
            return $history[count($history)-2];
        }
        return null;
    }
    
    public function getCurrentRoute()
    {
        $history = $this->session->get('history') ? $this->session->get('history') : array();
        $total = count($history);
        if($total) {
            return $history[$total-1];
        }
        return null;
    }

    public function generateRouteId($route)
    {
        if($route && is_array($route)) {
            $allowed_params = array('id');
            $id = $route['slug'] . '_' . $route['action'];
            if(isset($route['params']['id'])) {
                $id .= '_' . $route['params']['id'];
            }
            return $id;
        }
        return null;
    }
    
    
    /* Tabs */
    public function getTab($route_id)
    {
        if($route_id) {
            $tabs_by_slugs = $this->getAllTabs();
            foreach($tabs_by_slugs as $slug=>$tabs) {
                foreach($tabs as $tab) {
                    if($tab['route']['id'] == $route_id) {
                        return $tab;
                    }
                }
            }
        }
        
        return array();
    }
    
    public function removeTab($route_id)
    {
        if($route_id) {
            $tab = $this->getTab($route_id);
            $slug = $tab['route']['slug'];
            $tabs_by_slugs = $this->getAllTabs();
            if(isset($tabs_by_slugs[$slug])) {
                foreach($tabs_by_slugs[$slug] as $i=>$tab_tmp) {
                    if($tab_tmp['route']['id'] == $tab['route']['id']) {
                        array_splice($tabs_by_slugs[$slug], $i, 1);
                        $this->session->set('tabs', $tabs_by_slugs);
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    public function getTabPosition($route_id)
    {
        if($route_id) {
            $tabs_by_slugs = $this->getAllTabs();
            foreach($tabs_by_slugs as $slug=>$tabs) {
                foreach($tabs as $i=>$tab) {
                    if($route_id == $tab['route']['id']) {
                        return $i;
                    }
                }
            }
        }
        
        return null;
    }
    
    public function addTab($tab)
    {
        if($tab && is_array($tab)) {
            $tabs_by_slugs = $this->getAllTabs();
            $tabs_by_slugs[$tab['route']['slug']][] = $tab;
            $this->session->set('tabs', $tabs_by_slugs);
            return true;
        }
        return false;
    }
    
    public function addTabAtPosition($tab, $position)
    {
        if($tab && is_array($tab)) {
            $tabs_by_slugs = $this->getAllTabs();
            $tabs_by_slugs[$tab['route']['slug']][(int)$position] = $tab;
            $this->session->set('tabs', $tabs_by_slugs);
            return true;
        }
        return false;
    }
    
    public function updateTab($tab)
    {
        if($tab && is_array($tab)) {
            $tabs_by_slugs = $this->getAllTabs();
            if(isset($tabs_by_slugs[$tab['route']['slug']])) {
                foreach($tabs_by_slugs[$tab['route']['slug']] as $i=>$tab_tmp) {
                    if($tab_tmp['route']['id'] == $tab['route']['id']) {
                        $tabs_by_slugs[$tab['route']['slug']][$i] = $tab;
                        $this->session->set('tabs', $tabs_by_slugs);
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    public function getTabs($slug)
    {
        if($slug) {
            $tabs_by_slugs = $this->getAllTabs();
            return isset($tabs_by_slugs[$slug]) ? $tabs_by_slugs[$slug] : array();
        }
        return array();
    }
    
    public function getAllTabs()
    {
        return !$this->session->has('tabs') ? array() : $this->session->get('tabs');
    }
    
    public function getCurrentTab()
    {
        if($route = $this->getCurrentRoute()) {
            $current_tab = $this->getTab($route['id']);
            return $current_tab;
        }
        return array();
    }
    
    public function getLastTab($slug)
    {
        if($slug && ($tabs = $this->getTabs($slug))) {
            return $tabs[count($tabs)-1];
        }
        return null;
    }
    
    public function setCurrentTabAttribute($attribute, $value)
    {
        if($current_tab = $this->getCurrentTab()) {
            $current_tab[$attribute] = $value;
        
            $tabs_by_slugs = !$this->session->has('tabs') ? array() : $this->session->get('tabs');
            if(isset($tabs_by_slugs[$current_tab['route']['slug']])) {
                foreach($tabs_by_slugs[$current_tab['route']['slug']] as $i=>$tab) {
                    if($tab['route']['id'] == $current_tab['route']['id']) {
                        $tabs_by_slugs[$current_tab['route']['slug']][$i] = $current_tab;
                        $this->session->set('tabs', $tabs_by_slugs);
                        return true;
                    }
                }
            }
        }

        return false;
    }
}