<?php
namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Uicms\App\Service\Model;

class Import
{
    private $model;
    private $entity;
    protected $params;
    
    public function __construct(Model $model, ParameterBagInterface $params)
    {
        $this->model = $model;
        $this->params = $params;
    }

    public function import($entity, $directory)
    {
        $project_dir = $this->params->get('kernel.project_dir');
        $path = '/' . trim($project_dir, '/') . '/' . trim($directory, '/');
        $this->entity = $entity;
        $this->user = $this->model->get('User')->find(1);
        $this->db = $this->model->get($entity);

        $files = scandir($path);
        foreach($files as $file_name) {
            $file_path = $path . '/' . $file_name;
            $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
            switch($file_extension) {
                case 'csv':
                    $this->importCsv($file_path);
                break;
            }
        }
    }
    
    protected function importCsv($file_path, $separator=';')
    {
        $fp = fopen($file_path, 'r');
        $i = 0;
        while ($data = fgetcsv($fp, 4096, $separator)) {
            if ($i == 0) {
                $fields = $data;
            } else {
                $row = $this->db->new($this->user);
                foreach ($data as $j => $value) {
                    $key = $fields[$j];
                    $set_method = $this->db->method($key, 'set');
                    $row->$set_method($value);
                    $id = $this->db->persist($row);
                }
                print "Inserted ID = " . $row->getId();
            }
            $i++;
        }
        fclose($fp);
    }
}