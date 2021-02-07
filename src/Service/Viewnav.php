<?php
namespace Uicms\App\Service;

use Uicms\App\Service\Model;

class Viewnav
{	
	public function __construct(Model $model)
    {
		$this->model = $model;
    }
    
    public function get($entity_name, $row, $params)
    {
        $view_nav = array();
        $view_nav['total'] = $this->model->get($entity_name)->count($params);
        $view_nav['next'] = $this->model->get($entity_name)->getRow(array_merge($params, array('get_next'=>true, 'current_row'=>$row)));
        $view_nav['prev'] = $this->model->get($entity_name)->getRow(array_merge($params, array('get_prev'=>true, 'current_row'=>$row)));
        $view_nav['offset'] = $view_nav['total'] - $this->model->get($entity_name)->count(array_merge($params, array('get_next'=>true, 'current_row'=>$row)));
        
        return $view_nav;
    }
}