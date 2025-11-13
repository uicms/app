<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IsDateTimeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('is_date_time', [$this, 'isDateTime']),
        ];
    }

    public function isDateTime($value)
    {
        if($value instanceof \DateTime) {
            return true;
        }
        return false;
    }
}