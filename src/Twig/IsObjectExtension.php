<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IsObjectExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('is_object', [$this, 'isObject']),
        ];
    }

    public function isObject($value)
    {
        return is_object($value);
    }
}