<?php
namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Uploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $slug=true, $make_unique=true)
    {
        $file_name = $file->getClientOriginalName();
        $infos = pathinfo($file_name);
        
        if($slug) {
            $slugger = new AsciiSlugger();
            $file_name = strtolower($slugger->slug($file_name));
        }
        
        if($make_unique) {
            $file_name = $file_name . '-' . uniqid() . '.' . $infos['extension'];
        }
        
        try {
            $file->move($this->getTargetDirectory(), $file_name);
        } catch (FileException $e) {
            throw new \Exception('upload_error');
        }

        return $file_name;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}