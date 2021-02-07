<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SluggerExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('slugger', [$this, 'slug']),
        ];
    }

    public function slug($string)
    {
        $slugger = new AsciiSlugger();
        return strtolower($slugger->slug($string));
    }
}