<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\String\Slugger\AsciiSlugger;

class IncExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('inc', [$this, 'inc']),
        ];
    }

    public function inc($value, $argument=null)
    {
        if(null === $argument) {
            $argument = 1;
        }
        return (int)$value+$argument;
    }
}