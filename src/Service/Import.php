<?php
namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Uicms\App\Service\Model;
use Uicms\Admin\Form\DataTransformer\FileTransformer;

class Import
{
    private $model;
    private $entity;
    protected $params;
    
    public function __construct(Model $model, ParameterBagInterface $parameters)
    {
        $this->model = $model;
        $this->params = $parameters;
    }

    public function import($entity, $directory, $files_directory='')
    {
        $project_dir = $this->params->get('kernel.project_dir');
        $path = '/' . trim($project_dir, '/') . '/' . trim($directory, '/');
        $this->entity = $entity;
        $this->user = $this->model->get('User')->find(1);
        #$this->db = $this->model->get($entity);

        $files = scandir($path);
        foreach($files as $file_name) {
            $file_path = $path . '/' . $file_name;
            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
            switch($file_extension) {
                case 'csv':
                    $this->importCsv($file_path, ';', $files_directory);
                break;
            }
        }
    }
    
    protected function importCsv($file_path, $separator=';', $files_directory='')
    {
        $model = $this->model->get($this->entity);
        $dir = dirname($file_path);

        # Files directory
        if($files_directory) {
            $this->dir_files = $this->params->get('kernel.project_dir') . '/' . $files_directory;
        } else {
            $this->dir_files = $dir . "/files";
        }
        
        $fp = fopen($file_path, 'r');
        $i = 0;
        while ($data = fgetcsv($fp, 4096, $separator)) {

            if ($i == 0) {
                $fields = $data;
            } else {
                $new_row = $this->model->get($this->entity)->new();
                $is_empty_row = true;

                foreach ($data as $j => $value) {
                    $key = $fields[$j];
                    $value = trim($value);
                    $set_method = $model->method($key, 'set');
                    
                    if($field_config = $model->getField(['name'=>$key])) {
                        $value = $value ? $this->handleValue($field_config, $value) : null;
                        $new_row->$set_method($value);
                        $is_empty_row = false;
                    }
                }
                
                # Persist
                if(!$is_empty_row) {
                    $id = $model->persist($new_row);
                    print "Inserted ID = " . $new_row->getId() . "\n";
                    
                    # Links
                    $links = [];
                    foreach($data as $j => $value) {
                        $key = $fields[$j];

                        if(preg_match("'^__link__([a-zA-Z0-9_-]+?)__([a-zA-Z0-9_-]+)'", $key, $preg)) {
                            $entity_name = str_replace('_', '', ucwords($preg[1], '_'));
                            $field_name = $preg[2];
                            
                            if(isset($this->params->get('ui_config')['entity']['App\Entity\\' . $entity_name])) {
                                $links[$entity_name]['config'] = $this->model->get($entity_name)->getConfig();
                                $links[$entity_name]['config_field'][$field_name] = $this->model->get($entity_name)->getField(['name'=>$field_name]);
                                if(!isset($links[$entity_name]['data'])) {
                                    $links[$entity_name]['data'] = [];
                                } 
                                if(trim($value)) {
                                    $linked_values = explode(";", $value);
                                    foreach($linked_values as $k => $linked_value) {
                                        $links[$entity_name]['data'][$k][$field_name] = $this->handleValue($links[$entity_name]['config_field'][$field_name], trim($linked_value));
                                    }
                                }
                            }
                        }
                    }

                    foreach($links as $link_entity_name => $link_config) {
                        foreach ($link_config['data'] as $j => $row) {
                            if(isset($row[$link_config['config']['name_field']])) {
                                # Check if exists
                                if(!$linked_row = $this->model->get($link_entity_name)->getRow(['findby'=>[$link_config['config']['name_field'] => $row[$link_config['config']['name_field']]]])) {
                                    $linked_row = $this->model->get($link_entity_name)->new();
                                }

                                # Set each field value
                                foreach($row as $linked_field_name => $linked_field_value) {
                                    $linked_row_set_method = $this->model->get($link_entity_name)->method($linked_field_name, 'set');
                                    $linked_row->$linked_row_set_method($linked_field_value);
                                }

                                $this->model->get($link_entity_name)->persist($linked_row);

                                # Link to row
                                $model->mode('admin')->link([$id], $link_entity_name, [$linked_row]);
                            }
                        }
                    }
                }
            }
            
            $i++;
        }
        fclose($fp);
    }

    protected function handleValue($field_config, $value) {
        # Set value depending on its type
        if(isset($field_config['form']) && $field_config['form']) {
            switch($field_config['form']['type']) {
                case "EntityType":
                    $entity_model = $this->model->get($field_config['form']['options']['class']);
                    $entity_config = $entity_model->getConfig();
                    if(!$entity_row = $entity_model->getRow(['findby'=>[$entity_config['name_field'] => $value]])) {
                        $entity_row = $entity_model->new();
                        $entity_set_method = $entity_model->method($entity_config['name_field'], 'set');
                        $entity_row->$entity_set_method($value);
                        $entity_model->persist($entity_row);
                    }
                    return $entity_row;
                break;
                
                case "UIFileType":
                    if(file_exists($this->dir_files . '/' . $value)) {
                        $transformer = new FileTransformer($field_config, $this->params->get('ui_config'));
                        $file = new File($this->dir_files . '/' . $value);
                        $value = $transformer->reverseTransform($file);
                        return $value;
                    } else {
                        return false;
                    }
                break;
                
                case "DateType":
                    $value = new \Date($value);
                    return $value;
                break;

                case "DatetimeType":
                    $value = new \Datetime($value);
                    return $value;
                break;
                
                default:
                    return $value;
                break;
            }
        } else if(!isset($field_config['form']) || !$field_config['form']){
            switch($field_config['type']) {
                case "datetime":
                    $value = new \Datetime($value);
                    return $value;
                break;
            }
        }
        
    }
}