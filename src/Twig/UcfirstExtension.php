<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UcfirstExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('ucfirst', [$this, 'ucfirst']),
        ];
    }

    public function ucfirst($string)
    {
        return ucfirst($string);
    }
}