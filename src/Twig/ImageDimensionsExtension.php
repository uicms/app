<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageDimensionsExtension extends AbstractExtension
{
    protected $upload_folder = 'uploads';
    
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->ui_config = $parameters->get('ui_config');
        $this->project_dir = $parameters->get('kernel.project_dir');
        
        if(isset($ui_config['upload_folder'])) $this->upload_folder = $ui_config['upload_folder'];
    }
    
    public function getFilters(): array
    {
        return [
            new TwigFilter('width', [$this, 'getImageWidth']),
            new TwigFilter('height', [$this, 'getImageHeight']),
        ];
    }

    public function getImageWidth(string $path): ?int
    {
        $path = $this->project_dir . '/public/' . $this->upload_folder . '/' . $path; 
        $dimensions = @getimagesize($path);
        return $dimensions ? $dimensions[0] : null;
    }

    public function getImageHeight(string $path): ?int
    {
        $path = $this->project_dir . '/public/' . $this->upload_folder . '/' . $path; 
        $dimensions = @getimagesize($path);
        return $dimensions ? $dimensions[1] : null;
    }
}
