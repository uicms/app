<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\String\Slugger\AsciiSlugger;

class HtmlDecodeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('html_decode', [$this, 'decode']),
        ];
    }

    public function decode($string)
    {
        return html_entity_decode($string);
    }
}