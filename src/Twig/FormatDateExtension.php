<?php
namespace Uicms\App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormatDateExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('format_date', [$this, 'format']),
        ];
    }

    public function format($date, $format, $locale)
    {
        $locale = $locale . '_' . strtoupper($locale);
        setlocale(LC_TIME, "$locale.utf8");
        $result = strftime($format, $date->getTimestamp());
        return $result;
    }
}