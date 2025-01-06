<?php
namespace Uicms\App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\MimeTypes;

class BaseRepository extends ServiceEntityRepository
{
    protected $name = '';
    #protected $basename = '';
    protected $locale = '';
    protected $default_locale = 'fr';
    protected $mode = 'front';
    protected $flush_mode = 'auto';
    protected $security;
    protected $meta_fields = array( 'id', 
                                    'created', 
                                    'modified', 
                                    'published', 
                                    'is_concealed', 
                                    'is_locked', 
                                    'position', 
                                    'is_dir', 
                                    'locale',
                                    'parent');
    
    
    # Global config
    protected $global_config = array();
    protected $row_default_name = 'Untitled';
    protected $duplicate_prefix = 'Copy of';
    protected $upload_path = 'public/uploads';
    protected $preview_prefix = '_';
    protected $config = null;
    protected $debug = false;
    
    public function __construct(Security $security, ManagerRegistry $registry, UserPasswordEncoderInterface $passwd_encoder, ParameterBagInterface $parameters, $entity_name)
    {
        $this->name = $entity_name;
        $name_splitted = explode('\\', $entity_name);
        $this->basename = end($name_splitted);
        $this->security = $security;
        $this->passwd_encoder = $passwd_encoder;
        $this->parameters = $parameters;
        $this->global_config = $ui_config = $parameters->get('ui_config');
        $this->locale = $parameters->get('locale');
        $this->default_locale = $parameters->get('locale');
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            if($entity_name == $this->name) {
                $this->config = $entity;
                break;
            }
        }
        
        if(isset($ui_config['preview_prefix'])) $this->preview_prefix = $ui_config['preview_prefix'];
        if(isset($ui_config['upload_path'])) $this->upload_path = $ui_config['upload_path'];
        if(isset($ui_config['row_default_name'])) $this->row_default_name = $ui_config['row_default_name'];
        if(isset($ui_config['duplicate_prefix'])) $this->duplicate_prefix = $ui_config['duplicate_prefix'];
        
        parent::__construct($registry, $this->name);
    }
    
    /* Protected functions */
    protected function getQuery($params=array())
    {
        $query = $this->createQueryBuilder('t');
        $statement_argument = isset($params['statement_argument']) ? $statement_argument = $params['statement_argument'] : '';
        $parameters = array();
        
        # Table for positions
        isset($params['linked_to']) && $params['linked_to'] ? $position_table_alias = 'l' : $position_table_alias = 't';
        
        # Ordering
        $order_by = (isset($params['order_by']) && $params['order_by']) ? $params['order_by'] : $this->config['order_by'];
        $order_dir = (isset($params['order_dir']) && $params['order_dir']) ? $params['order_dir'] : $this->config['order_dir'];

        if(is_array($order_by)) {
            $order_by_method = 'get' . $order_by[0];
        } else {
            $order_by_method = 'get' . $order_by;
        }
        
        # Statement
        if(!isset($params['statement']) || !$params['statement']) {
            $params['statement'] = 'select';
        }
        switch($params['statement']) {
            case "update":
                $query->update($statement_argument);
            break;
            
            case "insert":
            break;
            
            case "delete":
                $query->delete();
            break;
            
            case "select":
                $query->select('u, t');
                $query->join('t.user', 'u');
                $query->leftJoin('t.parent', 'p');
                
                # Translations
                if($this->isTranslatable()) {                    
                    $query->leftJoin('t.translations', 'i', 'WITH', "i.locale = '" . $this->locale . "'");
                    $query->leftJoin('t.translations', 'fallback', 'WITH', "fallback.locale = '" . $this->default_locale . "'");
                    
                    if($this->isFieldTranslatable($order_by)) {
                        $query->addSelect("COALESCE(i.$order_by, fallback.$order_by) AS HIDDEN $order_by");
                        $query->orderBy($order_by, $order_dir);
                    } else {
                        $query->addSelect("COALESCE(i, fallback) AS HIDDEN translation");
                    }
                }
                
                # Is concealed                
                if($this->mode == 'front' && (!isset($params['preview_key']) || 
                    !$params['preview_key'] || $params['preview_key'] != $this->global_config['preview_key'])) {
                    $query->andWhere('t.is_concealed=:concealed');
                    $parameters['concealed'] = 0;
                }
                
                # Positions
                if(!isset($params['disable_positions']) || !$params['disable_positions']) {
                    $query->orderBy("$position_table_alias.position", 'asc');
                }
                
                # Order
                #if($order_table_alias != 'i') {
                    if(is_array($order_by)) {
                        foreach($order_by as $i=>$order_by_field) {
                            if(is_array($order_dir)) {
                                $order_dir_field = $order_dir[$i];
                            } else {
                                $order_dir_field = $order_dir;
                            }
                            $order_table_alias = $this->isFieldTranslatable($order_dir_field) && $order_dir_field != 'id' ? 'i' : 't';
                            $query->addOrderBy($order_table_alias . '.' . $order_by_field, $order_dir_field);
                        }
                    } else {
                        $order_table_alias = $this->isFieldTranslatable($order_by) && $order_by != 'id' ? 'i' : 't';
                        $query->addOrderBy($order_table_alias . '.' . $order_by, $order_dir);
                    }
                #}
                
                # Order by ID
                if($order_by != 'id') $query->addOrderBy('t.id', 'asc');
            break;
        }
        
        
        # By id
        if(isset($params['id']) && (int)$params['id']) {
            $query->andWhere('t.id = :id');
            $parameters['id'] = (int)$params['id'];
        }
        
        # Not in
        if(isset($params['not_in']) && $params['not_in'] && isset($params['not_in_list']) && (array)$params['not_in_list']) {
            $query->andWhere($query->expr()->notIn($params['not_in'], $params['not_in_list']));
        }
        
        # Set
        if(isset($params['set']) && $params['set'] && is_array($params['set'])) {
            foreach($params['set'] as $set_field=>$set_value) {
                $set_table_alias = $this->isFieldTranslatable($set_field) && $set_field!='id' ? 'i' : 't';
                $query->set($set_table_alias . '.' . $set_field, $set_value);
            }
        }

        # Group
        if(isset($params['group_by']) && $params['group_by']) {
            if($this->isFieldTranslatable($params['group_by'])) {
                $query->groupBy('i.' . $params['group_by']);
            } else {
                $query->groupBy('t.' . $params['group_by']);
            }
        }
        
        # Dir
        if((!isset($params['linked_to']) || !$params['linked_to']) && (!isset($params['search']) || !$params['search'])) {
            if(isset($params['dir']) && (int)$params['dir']) {
                $parent_query_string = "t.parent=".(int)$params['dir'];
                $query->andWhere($parent_query_string);
            } else if(isset($params['dir']) && !(int)$params['dir']){
                $parent_query_string = 't.parent IS NULL';
                $query->andWhere($parent_query_string);
            }
        } else {
            $parent_query_string = '';
        }
        
        # Next
        if(isset($params['get_next']) && $params['get_next'] && isset($params['current_row']) && $params['current_row']) {
            if(isset($params['offset'])) unset($params['offset']);
            if(isset($params['limit'])) unset($params['limit']);
            $current_value = $params['current_row']->$order_by_method();
            $current_id = $params['current_row']->getId();
            
            if(isset($params['linked_to']) && $params['linked_to']) {
                $function = 'get' . $this->getLinkEntityBasename(array($this->name, $this->normalize($params['linked_to'])));
                $current_position = $params['current_row']->$function()[0]->getPosition();
            } else {
                $current_position = $params['current_row']->getPosition();
            }
            $comparator = $order_dir == 'asc' ? '>' : '<';
            
            # Conditions
            $equal_condition = $current_value === null ? 'IS NULL' : '= :current_value';
            
            if($current_value === null && $comparator == '>') {
                $not_equal_condition = "$order_table_alias.$order_by IS NOT NULL";
            } else if($current_value !== null && $comparator == '<') {
                $parameters['current_value'] = $current_value;
                $not_equal_condition = "($order_table_alias.$order_by $comparator :current_value OR $order_table_alias.$order_by IS NULL)";
            } else {
                $parameters['current_value'] = $current_value;
                $not_equal_condition = "$order_table_alias.$order_by $comparator :current_value";
            }
            
            # Position order is prioritary
            $parameters['current_position'] = $current_position;
            $next_where = "$position_table_alias.position > :current_position";
            
            # OR
            if($order_by != 'position') {
                $query_string = "$position_table_alias.position = :current_position and $not_equal_condition";
                if(isset($parent_query_string) && $parent_query_string) $query_string .= ' AND ' . $parent_query_string;
                $next_where .= ' OR (' . $query_string . ')';
            }
 
            # OR
            if($order_by != 'id' && $order_by != 'position') {
                $parameters['current_id'] = $current_id;
                $query_string = "$position_table_alias.position = :current_position and $order_table_alias.$order_by $equal_condition and t.id > :current_id";
                if(isset($parent_query_string) && $parent_query_string) $query_string .= ' AND ' . $parent_query_string;
                $next_where .= ' OR (' . $query_string . ')';
            }
            
            $query->andWhere($next_where);
            #dd($query->getDql());
        }
        
        # Prev
        if(isset($params['get_prev']) && $params['get_prev'] && isset($params['current_row']) && $params['current_row']) {
            if(isset($params['offset'])) unset($params['offset']);
            if(isset($params['limit'])) unset($params['limit']);
            $current_value = $params['current_row']->$order_by_method();
            $current_id = $params['current_row']->getId();
            if(isset($params['linked_to']) && $params['linked_to']) {
                $function = 'get' . $this->getLinkEntityBasename(array($this->name, $this->normalize($params['linked_to'])));
                $current_position = $params['current_row']->$function()[0]->getPosition();
            } else {
                $current_position = $params['current_row']->getPosition();
            }
            $comparator = strtolower($order_dir) == 'asc' ? '<' : '>';
            $reverse_dir = strtolower($order_dir) == 'asc' ? 'desc' : 'asc';

            # Conditions
            if($current_value === null) {
                $equal_condition = 'IS NULL';;
            } else {
                $parameters['current_value'] = $current_value;
                $equal_condition = '= :current_value';
            }
            
            if($current_value === null && $comparator == '>') {
                $not_equal_condition = "$order_table_alias.$order_by IS NOT NULL";
            } else if($current_value !== null && $comparator == '>') {
                $parameters['current_value'] = $current_value;
                $not_equal_condition = "($order_table_alias.$order_by $comparator :current_value)";
            } else if($current_value !== null && $comparator == '<') {
                $parameters['current_value'] = $current_value;
                $not_equal_condition = "($order_table_alias.$order_by $comparator :current_value OR $order_table_alias.$order_by IS NULL)";
            }
            
            # Position order is prioritary
            $query->orderBy("$position_table_alias.position", 'desc');
            $parameters['current_position'] = $current_position;
            $prev_where = "$position_table_alias.position < :current_position";
            
            # OR
            if($order_by != 'position' && isset($not_equal_condition)) {
                $query->addOrderBy("$order_table_alias.$order_by", $reverse_dir);
                $query_string = "$position_table_alias.position = :current_position AND $not_equal_condition";
                if(isset($parent_query_string) && $parent_query_string) $query_string .= ' AND ' . $parent_query_string;
                $prev_where .= ' OR (' . $query_string . ')';
            }
            # OR
            if($order_by != 'id' && $order_by != 'position') {
                $parameters['current_id'] = $current_id;
                $query->addOrderBy('t.id', 'desc');
                $query_string = "$position_table_alias.position = :current_position and $order_table_alias.$order_by $equal_condition and t.id < :current_id";
                if(isset($parent_query_string) && $parent_query_string) $query_string .= ' AND ' . $parent_query_string;
                $prev_where .= ' OR (' . $query_string. ')';
            }
            $query->andWhere($prev_where);
            #dd($query->getDql());
        }
        
        # Findby
        if(isset($params['findby']) && $params['findby'] && is_array($params['findby'])) {
            $i = 0;
            foreach($params['findby'] as $field_name=>$value) {
                if($this->getField(['name'=>$field_name])) {
                    if($this->isFieldTranslatable($field_name)) {
                        $query->andWhere('i.' . $field_name . ' = :findby'.$i);
                    } else {
                        $query->andWhere('t.' . $field_name . ' = :findby'.$i);
                    }
                    $parameters['findby'.$i] = $value;
                } else if (isset($params['linked_to']) && $params['linked_to']) {
                    $linked_entity = $this->getEntityManager()->getRepository($this->normalize($params['linked_to']))->locale($this->locale);
                    $link_entity = $this->getLinkEntity(array($this->normalize($params['linked_to']), $this->name));
                    if($link_entity->getField(['name'=>$field_name])) {
                        $query->andWhere('l.' . $field_name . ' = :findby'.$i);
                        $parameters['findby'.$i] = $value;
                    }
                } else {
                    $query->andWhere('t.' . $field_name . ' = :findby'.$i);
                    $parameters['findby'.$i] = $value;
                }
                
                $i++;
            }            
        }
        
        # Search
        if(isset($params['search']) && $params['search']) {
            
            # Specify fields or not
            if(is_array($params['search'])) {
                $string = array_key_first($params['search']);
                $fields = is_array($params['search'][$string]) ? $params['search'][$string] : array($params['search'][$string]);
            } else {
                $string = $params['search'];
                $tmp = $this->getFields();
                $fields = [];
                foreach($tmp as $i=>$field) {
                    if($field['is_db']) {
                        $fields[] = $field['name'];
                    }
                }
            }


            # Get search terms
            preg_match_all("'([^ ]*?\".*?\"|[^ ]+) *'", addslashes($string), $preg);
            $search_terms = [];
            foreach($preg[1] as $i=>$term) {
                if(strlen($term)>1) {
                    $search_terms[] = $term;
                }
            }
            

            # Set query
            $search_where = [];
            
            foreach ($search_terms as $i=>$search_term) {

                $tmp = '(';
                
                foreach($fields as $k=>$field_name) {
                    if($k) $tmp .= " OR ";

                    if($this->isFieldTranslatable($field_name)) {
                        $tmp .= 'i.' . $field_name . ' LIKE :search' . $i;
                    } else {
                        $tmp .= 't.' . $field_name . ' LIKE :search' . $i;
                    }

                    # Truncate
                    $search_parameter = "%$search_term%";
                    if(isset($params['truncation']) && $params['truncation']) {
                        switch ($params['truncation']) {
                            case 'left':
                                $search_parameter = "$search_term%";
                            break;
                            
                            case 'right':
                                $search_parameter = "%$search_term";
                            break;
                        }
                    }

                    # Set parameter
                    $parameters['search' . $i] = $search_parameter;
                }

                $search_where[] = $tmp . ')';
            }

            if($search_where_string = implode(' AND ', $search_where)) {
                $query->andWhere($search_where_string);
            }
        }
        
        # Linked to
        if(isset($params['linked_to']) && $params['linked_to']) {
            $linked_entity = $this->getEntityManager()->getRepository($this->normalize($params['linked_to']))->locale($this->locale);
            $link_entity = $this->getLinkEntity(array($this->normalize($params['linked_to']), $this->name));
            
            $query->innerJoin('t.' . $link_entity->config['table_name'], 'l');
            $query->addSelect('l');
            
            if(isset($params['linked_to_id']) && $params['linked_to_id']) {
                $query->innerJoin('l.' . $linked_entity->config['table_name'], 'le', 'WITH', 'le.id=:linked_id');
                $parameters['linked_id'] = $params['linked_to_id'];
            }
        }
        
        # Offset and limit
        if(isset($params['offset'])) {
            $query->setFirstResult((int)$params['offset']);
        }
        if(isset($params['limit'])) {
            $query->setMaxResults((int)$params['limit']);
        }
        $query->setParameters($parameters);

        if($this->debug) {
            dd($query->getDql());
        }
        return $query;
    }
    
    protected function setRowData($row, $params=array()) 
    {
        if(null !== $row) {
            # Meta
            $row->_name = $this->row_default_name;
            $row->_file = null;
            $row->_thumbnail = null;
            $row->_text = null;
            $row->_links = array();
            $row->_form = null;
            
            # Linked position
            if(isset($params['linked_to']) && $params['linked_to']) {
                if($method = 'get' . str_replace('_', '', ucwords($this->getLinkTableName(array($this->normalize($params['linked_to']), $this->name)), '_'))) {
                    $row->_linked_position = $row->$method()->first()->getPosition();
                }
            }
            
            # Main field
            $method = $this->method($this->config['name_field']);
            if($row->$method()) $row->_name = $row->$method();

            # _name is object
            if(is_object($row->_name)) {
                $current_entity_name = $this->name;

                while(is_object($row->_name)) {
                    $name_field = $this->global_config['entity'][$current_entity_name]['name_field'];

                    if(isset($this->global_config['entity'][$current_entity_name]['form']['fields'][$name_field]['options']['class'])) {
                        
                        # Set foreign entity
                        $foreign_entity_name = $this->global_config['entity'][$current_entity_name]['form']['fields'][$name_field]['options']['class'];
                        $foreign_model = $this->model($foreign_entity_name);
                        
                        $method = $this->method($foreign_model->getConfig('name_field'));
                        $row->_name = $row->_name->$method();

                        $current_entity_name = $foreign_entity_name;
                    }
                }
            }
            
            # File
            if($file_field = $this->getField(array('form'=>['type'=>'UIFileType']))) {
                $method = 'get'.$file_field['form']['name'];
                if($row->_file = $row->$method()) {
                    $file_path = getcwd() . '/uploads/' . $row->_file;
                    if(file_exists($file_path) && !is_dir($file_path)) {
                        $mime_types = new MimeTypes();
                        $mime_type = $mime_types->guessMimeType($file_path);
                        $path_parts = pathinfo($file_path);
                        
                        if(strpos($mime_type, 'image') === 0) {
                            $row->_thumbnail = '_' . $row->_file;
                        }
                        
                        $video_thumbnail = '_' . $path_parts['filename'] . ".jpg";
                        if(strpos($mime_type, 'video') === 0 && file_exists(getcwd() . '/uploads/' .$video_thumbnail)) {
                            $row->_thumbnail = $video_thumbnail;
                        }
                    }
                }
            }
            
            # Text preview
            if($textarea_field = $this->getField(array('form'=>['type'=>'TextareaType']))) {
                $method = 'get'.$textarea_field['form']['name'];
                $row->_text = $row->$method();
            }
        }
        return $row;
    }
    
    
    /* Special functions */
    public function debug($value)
    {
        $this->debug = (boolean)$value;
        return $this;
    }
    
    public function mode($mode)
    {
        $this->mode = $mode;
        return $this;
    }
    
    public function setFlushMode($mode)
    {
        $this->flush_mode = $mode;
        return $this;
    }
    
    public function flush()
    {
        $em = $this->getEntityManager();
        return $em->flush();
    }
        
    public function locale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    
    public function meta($entity_name='')
    {
        return $this->getEntityManager()->getClassMetadata($entity_name ? $this->normalize($entity_name) : $this->name);
    }
    
    public function model($entity_name) 
    {
        return $this->getEntityManager()->getRepository($this->normalize($entity_name))->locale($this->locale);
    }
    
    public function method($field_name='id', $type='get') 
    {
        $field_name = str_replace('_', '', ucwords($field_name, '_'));
        return $type.$field_name;
    }
    
    public function normalize($entity_name)
    {
        if(strpos($entity_name, 'App\Entity') === false) {
            return 'App\Entity\\' . ucfirst($entity_name);
        }
        return $entity_name;
    }
    
    
    /* Properties */
    public function getName()
    {
        return $this->meta()->name;
    }
    
    public function getSlug()
    {
        if(isset($this->global_config['admin']['pages'])) {
            foreach($this->global_config['admin']['pages'] as $page) {
                if(isset($page['arguments']['entity_name']) && $page['arguments']['entity_name'] == $this->name) {
                     return $page['slug'];
                }
            }
        }
        return null;
    }
    
    public function getConfig($param=null)
    {
        if($param === null) {
            return $this->config;
        } else if(isset($this->config[$param])) {
            return $this->config[$param];
        } else {
            return null;
        }
        return null;
    }
    
    public function isTranslatable()
    {
        $mappings = $this->meta()->getAssociationMappings();
        return isset($mappings['translations']);
    }
    
    public function isFieldTranslatable($field_name)
    {
        if($this->isTranslatable()) {
            $columns = $this->meta($this->name . 'Translation')->getColumnNames();
            foreach($columns as $column_name) {
                if($field_name == $column_name) return true;
            }
        }     
        return false;
        
    }
    
    public function getFields($params=array())
    {
        if(!isset($this->global_config['entity'][$this->name]['form']) || !$this->global_config['entity'][$this->name]['form']) {
            return [];
        }

        $all_fields = array();        

        # Translatables
        if($this->isTranslatable()) {
            $translations = $this->getEntityManager()->getRepository($this->name . 'Translation');
            $translatable_fields = $this->meta($this->name . 'Translation')->getFieldNames();
            if($translatable_fields[0] == 'id') unset($translatable_fields[0]);

            foreach($translatable_fields as $i=>$field_name) {
                $field_attr = $this->meta($this->name . 'Translation')->getFieldMapping($field_name);
                $field_attr['name'] = $field_name;
                $field_attr['is_meta'] = in_array($field_name, $this->meta_fields) ? true : false;
                $field_attr['is_db'] = true;
                
                foreach($this->global_config['entity'][$this->name]['form']['translations'] as $form_field_name=>$form_field) {
                    if($field_name == $form_field_name) {
                        $field_attr['form'] = $form_field;
                    }
                }
                $all_fields[] = $field_attr;
            }
        }
        
        # Not translatables
        $regular_fields = $this->meta()->getFieldNames();
        foreach($regular_fields as $i=>$field_name) {
            $field_attr = $this->meta()->getFieldMapping($field_name);
            $field_attr['name'] = $field_name;
            $field_attr['is_meta'] = in_array($field_name, $this->meta_fields) ? true : false;
            $field_attr['is_db'] = true;
            
            foreach($this->global_config['entity'][$this->name]['form']['fields'] as $form_field_name=>$form_field) {
                if($field_name == $form_field_name) {
                    $field_attr['form'] = $form_field;
                }
            }
            $all_fields[] = $field_attr;
        }
        
        # Complete with form fields
        foreach($this->global_config['entity'][$this->name]['form']['fields'] as $form_field_name=>$form_field) {
            $exists = false;
            foreach($all_fields as $i=>$field) {
                if($field['name'] == $form_field_name) {
                    $exists = true;
                    break;
                }
            }
            if(!$exists) {
                $data = array('name'=>$form_field_name);
                $data['is_meta'] = in_array($form_field_name, $this->meta_fields) ? true : false;
                $data['is_db'] = false;
                $data['form'] = $form_field;
                $all_fields[] = $data;
            }
        }
        
        if($params) {
            $fields = array();
            foreach($all_fields as $i=>$field) {
                $condition = true;
                foreach($params as $param=>$value) {
                    if(is_array($value)) {
                        $key = array_keys($value)[0];
                        if(!isset($field[$param][$key]) || $field[$param][$key] != $value[$key]) {
                            $condition = false;
                        }
                    } else if(!isset($field[$param]) || $field[$param] != $value) {
                        $condition = false;
                    }
                }
                if($condition) {
                    $fields[] = $field;
                }
            }
            return array_unique($fields, SORT_REGULAR);
        } else {
            return array_unique($all_fields, SORT_REGULAR);
        }
    }
    
    public function getField($params=array())
    {
        $fields = $this->getFields($params);
        return $fields ? $fields[0] : array();
    }
    
    public function getPath($id, $basename=false, $mode='ASSOC', $spr='/')
    {
        if($id) {
            $table_name = $this->name;
            $row = $this->getRowById($id);
            $method = $this->method($this->config['name_field']);
            $main_field_value = $row->$method();
            $path = array();
            
            if($basename) {
                switch ($mode) {
                    case 'ASSOC':
                        $path = array($row);
                    break;
                    case 'ID':
                        $path = array($id);
                    break;
                    case 'STRING':
                        $path[] = $main_field_value;
                    break;
                    case 'NAME':
                        $path[] = $main_field_value;
                    break;
                }
            }
            
            while($row->getParent() !== null) {
                $row = $this->getRowById($row->getParent()->getId());
                $main_field_value = $row->$method();

                switch ($mode) {
                    case 'ASSOC':
                        $path[] = $row;
                    break;
                    case 'ID':
                        $path[] = $row->getId();
                    break;
                    case 'STRING':
                        $path[] = $main_field_value;
                    break;
                    case 'NAME':
                        $path[] = $main_field_value;
                    break;
                }
            }
            
            if ($mode == 'ASSOC' || $mode == 'ID') {
                return array_reverse($path);
            } else if ($mode == 'STRING') {
                return implode($spr, array_reverse($path));
            } else if ($mode == 'NAME') {
                return array_reverse($path);
            }
        } else {
            if ($mode == 'ASSOC' || $mode == 'ID' || $mode == 'NAME') {
                return array();
            } else if ($mode == 'STRING') {
                return '';
            }
        }
    } 
    
    
    /* Links */
    public function getLinkablesEntities()
    {
        $results = array();
        $linkables_entities = array();
        
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            
            if(isset($entity['link']) && in_array($this->name, $entity['link'])) {
                
                foreach($entity['link'] as $linkable_entity) {
                    
                    if(!in_array($linkable_entity, $linkables_entities) && $linkable_entity != $this->name) {
                        $linkables_entities[] = $linkable_entity;
                        $results[] = $this->model($linkable_entity);
                    }
                }
            }
        }
        return $results;
    }
    
    public function getLinkEntity($entities=array())
    {
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            if(isset($entity['link']) && in_array($entities[0], $entity['link']) && in_array($entities[1], $entity['link'])) {
                return $this->getEntityManager()->getRepository($entity_name)->mode('admin')->locale($this->locale);
            }
        }
        return null;
    }
    
    public function getLinkEntityName($entities=array())
    {
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            if(isset($entity['link']) && in_array($entities[0], $entity['link']) && in_array($entities[1], $entity['link'])) {
                return $entity_name;
            }
        }
        
        return null;
    }
  
    public function getLinkEntityBasename($entities=array())
    {
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            if(isset($entity['link']) && in_array($entities[0], $entity['link']) && in_array($entities[1], $entity['link'])) {
                $basename = explode('\\', $entity_name);
                return end($basename);
            }
        }
        
        return null;
    }
    
    public function getLinkTableName($entities=array())
    {
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            if(isset($entity['link']) && in_array($entities[0], $entity['link']) && in_array($entities[1], $entity['link'])) {
                return $entity['table_name'];
            }
        }
        
        return null;
    }
    
    
    /* FK links */
    public function getForeignKeys()
    {
        $fks = [];
        
        foreach($this->global_config['entity'] as $entity_name=>$entity) {
            $fields = [];
            if(isset($entity['form']['translations'])) {
                $fields = array_merge($fields, $entity['form']['translations']);
            }
            if(isset($entity['form']['fields'])) {
                $fields = array_merge($fields, $entity['form']['fields']);
            }
            foreach($fields as $field) {
                if($field['type'] == 'EntityType' && $field['options']['class'] == $this->name) {
                    $field['entity_name'] = $entity_name;
                    $field['db_name'] = $this->str2SnakeCase($field['name']);
                    $field['entity'] = $this->getEntityManager()->getRepository($entity_name)->mode('admin')->locale($this->locale);
                    $fks[] = $field;
                }
            }
        }
        
        return $fks;
    }
    
    
    /* TMP */
    function str2SnakeCase($str) {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $str, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
            strtolower($match) :
            lcfirst($match);
          }
          return implode('_', $ret);
    }
    /* TMP */


    /* Queries on data */
    public function count($params=array())
    {
        unset($params['limit']);
        unset($params['offset']);
        $query = $this->getQuery($params);
        return $query->select('COUNT(DISTINCT(t.id))')->getQuery()->getSingleScalarResult();
    }
    
    public function getOne($params=array())
    {
        
    }
    
    public function getOneById($field, $id)
    {
        
    }
    
    public function getRow($params=array())
    {
        $query = $this->getQuery($params);
        
        # Add conditions
        $query->setMaxResults(1);

        # Result
        $row = $query->getQuery()->getOneOrNullResult();
        
        return $this->setRowData($row);
    }
    
    public function getRowById($id)
    {
        $query = $this->getQuery(array('id'=>$id));
        
        # Result
        $row = $query->getQuery()->getOneOrNullResult();
        
        return $this->setRowData($row);
    }
    
    public function getAll($params=array())
    {
        $paginator = new Paginator($this->getQuery($params));
        $rows = array();
        foreach($paginator as $i=>$row) {
            $rows[] = $this->setRowData($row, $params);
        }
        return $rows;
    }
    
    
    /* Actions */
    public function new($user=null)
    {
        $new = new $this->name;
        if($this->isTranslatable()) {
            $new->setDefaultLocale($this->default_locale);
        }
        if(null === $user) {
            $user = $this->security->getUser();
        }
        $new->setUser($user);
        $new->setCreated(new \Datetime);
        $new->setModified(new \Datetime);
        $new->setPublished(new \Datetime);
        return $this->setRowData($new);
    }
    
    public function persist($data, $current=null)
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_PERSIST')) {
            
                if(!$data->getUser()) {
                    $repo = $this->getEntityManager()->getRepository('App\Entity\User');
                    $data->setUser($repo->find(1));
                }
                
                $fields = $this->getFields();
                foreach($fields as $i=>$field) {
                    if(!isset($field['is_meta']) || !$field['is_meta']) {
                        $get_method = $this->method($field['name']);
                        $set_method = $this->method($field['name'], 'set');
                       
                        # Password Type
                        if(isset($field['form']['type']) && $field['form']['type'] == 'RepeatedType') {
                            $dest_set_method = 'set' . $field['form']['dest'];
                            $password = $this->passwd_encoder->encodePassword($data, $data->$get_method());
                            $data->$dest_set_method($password);
                        }
                        
                        # File Type
                        if(isset($field['form']['type']) && $field['form']['type'] == 'UIFileType') {
                            
                            # Get current value in DB
                            if($data->getId()) {
                                $connection = $this->getEntityManager()->getConnection();
                                $stmt = $connection->prepare("SELECT " . $field['name'] . " FROM " . $this->getConfig('table_name') . " WHERE id=" . $data->getId());
                                $stmt->execute();
                                $result = $stmt->fetch();
                                $current_value = $result[$field['name']];
                            } else {
                                $current_value = '';
                            }
                            
                            # Get current data if no value provided
                            if(!$data->$get_method() && $current_value) {
                                $data->$set_method($current_value);
                            }
                            
                            # Upload file if different
                            if($data->$get_method() && $current_value && $data->$get_method() != $current_value) {
                                $path = $this->upload_path . '/' . $current_value;
                                $path_thumbnail = $this->upload_path . '/' . $this->preview_prefix . $current_value;
                                if(file_exists($path) && !is_dir($path)) {
                                    unlink($path);
                                }
                                if(file_exists($path_thumbnail) && !is_dir($path_thumbnail)) {
                                    unlink($path_thumbnail);
                                }
                            }
                        }
                    }
                }

            
            if($this->isTranslatable()) {
                $data->mergeNewTranslations();
            }
            $em = $this->getEntityManager();
            if($data->getCreated() == null) {
                $data->setCreated(new \Datetime);
            }
            if($data->getPublished() == null) {
                $data->setPublished(new \Datetime);
            }
            $data->setModified(new \Datetime);

            $em->persist($data);
            
            if($this->flush_mode == 'auto') {
                $em->flush();
            }
            
            return $data->getId();
        } else {
            throw new AccessDeniedException('access_denied');
        }
        
    }

    public function publish($selection)
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_PERSIST')) {
            $em = $this->getEntityManager();
            foreach($selection as $id) {
                $row = $this->find($id);
                $row->setIsConcealed(0);
                $em->persist($row);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
            return true;
        } else {
            throw new AccessDeniedException('access_denied');
        }
    }
    
    public function conceal($selection)
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_PERSIST')) {
            $em = $this->getEntityManager();
            foreach($selection as $id) {
                $row = $this->find($id);
                $row->setIsConcealed(1);
                $em->persist($row);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
            return true;
        } else {
            throw new AccessDeniedException('access_denied');
        }
    }
    
    public function duplicate($selection)
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_PERSIST')) {
            $em = $this->getEntityManager();
            
            foreach($selection as $id) {
                $row = $this->find($id);
                $copy = $this->new($row->getUser());
                $fields = $this->getFields();
                foreach($fields as $i=>$field) {
                    if(!isset($field['is_meta']) || !$field['is_meta']) {
                        $get_method = $this->method($field['name']);
                        $set_method = $this->method($field['name'], 'set');
                        $value = $row->$get_method();
                        
                        # Name field modified with prefix
                        if($field['name'] == $this->config['name_field']) {
                            $value = $this->duplicate_prefix . ' ' . $value;
                        }
                        
                        # Duplicate file
                        if($field['form']['type'] == 'UIFileType' && $value) {
                            $info = pathinfo($value);
                            $length = strlen($info['filename']) - 14;
                            $original_file_name = substr($info['filename'], 0, $length);
                            $old_value = $value;
                            $value =  $original_file_name . '-' . uniqid() . '.' . $info['extension'];
                            copy($this->upload_path . '/' . $old_value, $this->upload_path . '/' . $value);
                            copy($this->upload_path . '/' . $this->preview_prefix . $old_value, $this->upload_path . '/' . $this->preview_prefix . $value);
                        }
                        
                        # Set value in copy
                        $copy->$set_method($value);
                    }
                }
                if($this->isTranslatable()) {
                    $copy->mergeNewTranslations();
                }
                $em->persist($copy);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
            return true;
        } else {
            throw new AccessDeniedException('access_denied');
        }
    }
    
    public function delete($selection)
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_DELETE')) {
            if(!is_array($selection)) {
                $selection = [$selection];
            }
            $em = $this->getEntityManager();
            foreach($selection as $id) {
                $row = $this->find($id);
                
                if($row->getIsDir() && $this->count(['dir'=>$id])) {
                    $exception = 'dir_not_empty';
                    break;
                }
                
                # Detele links
                $linkables_entities = $this->getLinkablesEntities();
                foreach($linkables_entities as $i=>$linkable_entity) {
                    $link_table = $this->getLinkEntity(array($linkable_entity->name, $this->name));
                    $em = $this->getEntityManager();
                    $query = $em->createQueryBuilder()->from($link_table->name, 't')->delete();
                    $query->where("t." . $this->config['table_name'] . " = :id")->setParameter('id', $row->getId());
                    $query->getQuery()->execute();
                }
                
                
                # Delete files
                $fields = $this->getFields();
                foreach($fields as $i=>$field) {
                    if(!isset($field['is_meta']) || !$field['is_meta']) {
                        $get_method = $this->method($field['name']);
                        if(isset($field['form']['type']) && $field['form']['type'] == 'UIFileType') {
                            $path = $this->upload_path . '/' . $row->$get_method();
                            $path_thumbnail = $this->upload_path . '/' . $this->preview_prefix . $row->$get_method();
                            if(file_exists($path) && !is_dir($path)) {
                                unlink($path);
                            }
                            if(file_exists($path_thumbnail) && !is_dir($path_thumbnail)) {
                                unlink($path_thumbnail);
                            }
                        }
                    }
                }
                
                $em->remove($row);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
            
            if(isset($exception)) {
                throw new \Exception($exception);
            }
            
            return true;
        } else {
            throw new AccessDeniedException('access_denied');
        }
    }
    
    public function position($selection, $position, $params=array())
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_PERSIST')) {
            $em = $this->getEntityManager();
            
            # Define params
            $total = $this->count($params);
            
            #$paginator = new \Uicms\App\Service\Paginator($params['offset'], $params['limit'], $total);
            #$params['current_row'] = $this->getRow(array_merge($params, array('offset'=>$params['offset']+$paginator->total_current-1)));
            #$params['get_next'] = true;
            
            if(isset($params['linked_to']) && $params['linked_to']) {
                $split = explode("\\", $params['linked_to']);
                $link_function_get = 'get' . end($split);
                
                $rows = $this->getAll($params);
                foreach($rows as $i=>$row) {
                    $function_get = 'get' . $this->getLinkEntityBasename(array($this->name, $params['linked_to']));
                    $current_position = $row->$function_get()[0]->getPosition();
                    $new_position = max($position[count($position)-1], $current_position + 1 + $position[count($position)-1]);
                    foreach ($this->find($row->getId())->$function_get() as $j=>$link_row) {
                        if($link_row->$link_function_get()->getId() == $params['linked_to_id']) {
                            $row->$function_get()[$j]->setPosition($new_position);
                        }
                    }
                    if($this->flush_mode == 'auto') {
                        $em->flush();
                    }
                }
                
            } else {
                $params['statement'] = 'update';
                $params['set'] = array('position' => 't.position + ' . ($position[count($position)-1]+1));
                $result = $this->getQuery($params)->getQuery()->getResult();
            }

            # Update positions of selection
            foreach($selection as $i=>$id) {
                if(isset($params['linked_to']) && $params['linked_to']) {
                    $function_get = 'get' . $this->getLinkEntityBasename(array($this->name, $params['linked_to']));
                    foreach ($this->find($id)->$function_get() as $j=>$link_row) {
                        if($link_row->$link_function_get()->getId() == $params['linked_to_id']) {
                            $this->find($id)->$function_get()[$j]->setPosition($position[$i]);
                        }
                    }
                } else {
                    $this->find($id)->setPosition($position[$i]);
                }
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
            return true;
        } else {
            throw new AccessDeniedException('access_denied');
        }
    }
    
    public function move($selection, $target)
    {
        if($this->security->getUser() === null || $this->security->isGranted('ROLE_PERSIST')) {
            $em = $this->getEntityManager();
            foreach($selection as $id) {
                $row = $this->find($id);
                $parent = $this->find($target);
                $row->setParent($parent);
                $em->persist($row);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
            return true;
        } else {
            throw new AccessDeniedException('access_denied');
        }
    }
    
    public function link($selection, $linked_entity_name, $linked_selection, $link_data=[])
    {
        $model_link = $this->getLinkEntity(array($this->normalize($linked_entity_name), $this->name));
        $model_linked = $this->getEntityManager()->getRepository($this->normalize($linked_entity_name))->locale($this->locale);
        $set_method = 'set' . preg_replace('/^.+\\\\/', '', $this->getName());
        $set_method_linked = 'set' . preg_replace('/^.+\\\\/', '', $model_linked->getName());
        $table_name = $this->config['table_name'];
        $linked_table_name = $model_linked->config['table_name'];

        foreach($selection as $i=>$id) {
            foreach($linked_selection as $j=>$id_linked) {
                $row = $this->find($id);
                $row_linked = $model_linked->find($id_linked);
                
                if(!$link = $model_link->getRow(['findby'=>[$table_name=>$id, $linked_table_name=>$id_linked]])) {
                    eval("\$link = new \\" . $model_link->getName() . ';');
                    $link->setCreated(new \Datetime);
                    $link->setUser($this->security->getUser());
                    $link->$set_method($row);
                    $link->$set_method_linked($row_linked);
                }
                
                $link->setModified(new \Datetime);
                $link->setPublished(new \Datetime);

                if($link_data) {
                    foreach($link_data[$i] as $field_name=>$value) {
                        $data_set_method = 'set' . ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $field_name))));
                        $link->$data_set_method($value);
                    }
                }

                $em = $this->getEntityManager();
                $em->persist($link);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
        }
    }
    
    public function unlink($selection, $linked_entity_name, $linked_selection=[])
    {
        $model_link = $this->getLinkEntity(array($this->normalize($linked_entity_name), $this->name));
        $model_linked = $this->getEntityManager()->getRepository($this->normalize($linked_entity_name))->locale($this->locale);
        $set_method = 'set' . preg_replace('/^.+\\\\/', '', $this->getName());
        $set_method_linked = 'set' . preg_replace('/^.+\\\\/', '', $model_linked->getName());
        $table_name = $this->config['table_name'];
        $linked_table_name = $model_linked->config['table_name'];
        
        foreach($selection as $i=>$id) {
            $row = $this->find($id);

            if($linked_selection) {
                foreach($linked_selection as $j=>$id_linked) {
                    $row_linked = $model_linked->find($id_linked);
                    $link = $model_link->findOneBy(array($linked_table_name => $row_linked, $table_name => $row));

                    $em = $this->getEntityManager();
                    $em->remove($link);
                    if($this->flush_mode == 'auto') {
                        $em->flush();
                    }
                }
            } else {
                $links_results = $model_link->findBy([$table_name=>$row]);
                foreach($links_results as $link) {
                    $em = $this->getEntityManager();
                    $em->remove($link);
                    if($this->flush_mode == 'auto') {
                        $em->flush();
                    }
                }
            }
            
        }
    }
    
    public function linkChildren($selection, $parent_entity_name, $field_name, $parent_selection)
    {
        $model_parent = $this->getEntityManager()->getRepository($this->normalize($parent_entity_name))->mode('admin')->locale($this->locale);
        $set_method = 'set' . preg_replace('/^.+\\\\/', '', $field_name);

        foreach($selection as $i=>$id) {
            $row = $this->find($id);
            
            foreach($parent_selection as $j=>$id_parent) {
                $row_parent = $model_parent->find($id_parent);
                $row->$set_method($row_parent);
                
                $em = $this->getEntityManager();
                $em->persist($row);
                if($this->flush_mode == 'auto') {
                    $em->flush();
                }
            }
        }
    }
}