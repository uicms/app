<?php

namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManager;
use Uicms\App\Service\Model;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Filters
{
    private $params = [];
    private $model;
    private $request;
    private $ui_config;
    private $mode;
    private $filters = [];
    private $session;
    
    public function __construct(ParameterBagInterface $parameters, Model $model, RequestStack $request, SessionInterface $session)
    {
        $this->params = $parameters;
        $this->ui_config = $parameters->get('ui_config');
        $this->model = $model;
        $this->request = $request->getCurrentRequest();
        $this->session = $session;
    }
    
    public function getFilters($entity_name, $config=[], $params=[])
    {
        $this->config = $config;
        if(isset($config['multiple']) && $config['multiple']) {
            $this->mode = 'multiple';
        } else {
            $this->mode = 'single';
        }
        if(isset($params['limit'])) {
            unset($params['limit']);
        }
        if(isset($params['offset'])) {
            unset($params['offset']);
        }
        $this->result = [];
        $this->result['params'] = $params;
        $this->result['actives'] = [];
        $this->result['filters'] = [];
        $this->result['url'] = '';
        $this->result['mode'] = $this->mode;
        $this->result['count_displayed'] = 0;
        
        foreach($config['settings'] as $filter_name => $filter_config) {
            
            if(in_array($entity_name, $filter_config['data'])) {

                # Params of the filter
                if(!isset($filter_config['params'])) {
                    $filter_config['params'] = [];
                }
                # Default type
                if(!isset($filter_config['type'])) {
                    $filter_config['type'] = 'choice';
                }

                # [TYPE] Input
                if($filter_config['type'] == 'input') {
                    # Is active?
                    if($active_filter_value = $this->request->get($filter_config['param_name'])) {
                        $filter_config['param_value'] = $active_filter_value;
                        $this->result['actives'][] = (object) ['_name'=>$filter_config['i18n'], 'config'=>$filter_config];
                        $this->result['params'][$filter_config['param_name']] = $active_filter_value;
                    }
                }

                # [TYPE] Boolean
                if($filter_config['type'] == 'boolean') {
                    # Actives
                    if($active_filter_value = $this->request->get($filter_config['param_name'])) {
                        $this->result['actives'][] = (object) ['_name'=>$filter_config['i18n'], 'config'=>$filter_config];
                        $this->result['params'][$filter_config['param_name']] = $active_filter_value;
                    }
                }

                # [TYPE] Choice
                if($filter_config['type'] == 'choice') {
                    
                    # If options are from entity
                    if(isset($filter_config['entity']) && $filter_config['entity']) {
                        
                        # Get options from Entity
                        $filter_config['options'] = $this->model->get($filter_config['entity'])->getAll(
                            array_merge (
                                $filter_config['params'], 
                                [
                                    'linked_to_'. strtolower($entity_name) => true, 
                                    'disable_positions'=>true,
                                ]
                            )
                        );

                        # Actives
                        if($active_filter_values = $this->request->get($filter_config['param_name'])) {
                            foreach($active_filter_values as $active_filter_value) {
                                
                                # Get row by id or specified field
                                if(isset($filter_config['value_field'])) {
                                    $active = $this->model->get($filter_config['entity'])->getRow(['findby'=>[$filter_config['value_field']=>$active_filter_value]]);
                                } else {
                                    $active = $this->model->get($filter_config['entity'])->getRowById($active_filter_value);
                                }
                                #$active->_name = $active_filter_value;
                                $active->config = $filter_config;

                                $this->result['actives'][] = $active;
                                $this->result['params'][$filter_config['param_name']][] = $active_filter_value;
                            }
                        } 
                    }
                }
                

                # Add to result
                $this->result['filters'][$filter_config['param_name']] = $filter_config;
            }
            
        }

        # Loop again on filters
        foreach($config['settings'] as $filter_name => $filter_config) {
            
            if(in_array($entity_name, $filter_config['data'])) {
                
                # Default type
                if(!isset($filter_config['type'])) {
                    $filter_config['type'] = 'choice';
                }

                # [TYPE] Choice
                if($filter_config['type'] == 'choice') {

                    # Filtering options : from entity
                    if(isset($filter_config['entity']) && $filter_config['entity']) {
                        foreach($this->result['filters'][$filter_config['param_name']]['options'] as $i=>$option) {
                            
                            $keep_options = isset($filter_config['keep_options']) && $filter_config['keep_options'] ? true : false;
                            $option->_active = false;
                            $option->_has_link = true;


                            if(isset($this->result['params'][$filter_config['param_name']]) && 
                               in_array($option->getId(), $this->result['params'][$filter_config['param_name']])
                            ) {
                                $option->_active = true;
                                $option->_has_link = false;
                            } else {
                                $params_tmp = $this->result['params'];
                                $params_tmp[$filter_config['param_name']][] = $option->getId();

                                # Remove option if has no results
                                if((!$has_results = $this->model->get($entity_name)->count($params_tmp)) && !$keep_options) {
                                    unset($this->result['filters'][$filter_config['param_name']]['options'][$i]);
                                } else if((!$has_results && $keep_options) || isset($this->result['params'][$filter_config['param_name']])){
                                    $option->_has_link = false;
                                }
                            }
                        }

                        if(count($this->result['filters'][$filter_config['param_name']]['options']) && (!isset($filter_config['is_hidden']) || (isset($filter_config['is_hidden']) && !$filter_config['is_hidden']))) {
                            $this->result['count_displayed']++;
                        }
                    }



                    # Filtering options : manual
                }
            }

        }

        /* Urls */
        foreach($this->result['actives'] as $active) {

            $params_tmp = $this->result['params'];
            
            foreach($params_tmp as $param_name=>$param_values) {

                if($this->isFilterParam($param_name)) {

                    # [TYPE] Input
                    if($active->config['type'] == 'input' && $param_values == $active->config['param_value']) {
                        unset($params_tmp[$param_name]);
                    }

                    # [TYPE] Choice
                    if($active->config['type'] == 'choice' && is_array($param_values)) {
                        foreach($param_values as $i=>$value) {
                            if(isset($active->config['entity']) && $active->config['entity'] && $value == $active->getId()) {
                                unset($params_tmp[$param_name][$i]);
                            }
                        }
                    }

                    # [TYPE] boolean
                    if($active->config['type'] == 'boolean' && $param_values == $active->config['param_value']) {
                        unset($params_tmp[$param_name]);
                    }
                    
                }
            }

            $active->url_remove = $this->getUrl($params_tmp);
        }

        $this->result['url'] = $this->getUrl($this->result['params']);

        return $this->result;
    }

    function getUrl($params)
    {
        $url = '';
        foreach($params as $param_name=>$values) {
            if($this->isFilterParam($param_name)) {
                if(is_array($values)) {
                    foreach($values as $value) {
                        $url .= $param_name . '[]=' . $value . '&';
                    }
                } else {
                    $url .= $param_name . '=' . $values . '&';
                }
            }
        }
        return $url;
    }

    function isFilterParam($params_name)
    {
        foreach($this->config['settings'] as $filter_name => $filter_config) {
            if($filter_config['param_name'] == $params_name) {
                return true;
            }
        }
        return false;
    }
}