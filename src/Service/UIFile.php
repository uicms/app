<?php
namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;

class UIFile
{
    function __construct($ui_config)
    {
        $this->ui_config = $ui_config;

        if(isset($ui_config['upload_path'])) $this->upload_path = $ui_config['upload_path'];
        if(isset($ui_config['upload_folder'])) $this->upload_folder = $ui_config['upload_folder'];
        if(isset($ui_config['image_max_width'])) $this->max_width = $ui_config['image_max_width'];
        if(isset($ui_config['image_max_height'])) $this->max_height = $ui_config['image_max_height'];
        if(isset($ui_config['image_preview_max_width'])) $this->preview_max_width = $ui_config['image_preview_max_width'];
        if(isset($ui_config['image_preview_max_height'])) $this->preview_max_height = $ui_config['image_preview_max_height'];
        if(isset($ui_config['image_preview_prefix'])) $this->preview_prefix = $ui_config['image_preview_prefix'];
        if(isset($ui_config['video_generate_thumbnail'])) $this->video_generate_thumbnail = $ui_config['video_generate_thumbnail'];
    }

    public function rotate($file_name, $rotation)
    {
        $rotation = $rotation * -1;
        $file = new File($this->upload_path . '/' . $file_name);
        $mime_type = $file->getMimeType();

        if(strpos($mime_type, 'image') === 0 && strpos($mime_type, 'svg') === false) {
            $img = Image::make($this->upload_path . '/' . $file_name);
            $img->rotate($rotation);
            $img->save($this->upload_path . '/' . $file_name);
            $thumbnail = Image::make($this->upload_path . '/_' . $file_name);
            $thumbnail->rotate($rotation);
            $thumbnail->save($this->upload_path . '/_' . $file_name);
        }

        return true;
    }

    public function getProperties($file_name) {
        $manager = new ImageManager(array('driver' => 'imagick'));
        $img = $manager->make($this->upload_path . '/' . $file_name);
        $properties = [];
        $properties['exif'] = $img->exif();
        $properties['iptc'] = $img->iptc();

        return json_encode($properties);
    }
}