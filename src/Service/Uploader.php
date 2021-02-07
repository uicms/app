<?php
namespace Uicms\App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $original_filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
       	#$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $file_name = $original_filename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $file_name);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $file_name;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}