<?php
namespace App\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use DateTime;

class Date extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('date', [$this, 'date']),
            new TwigFilter('datetime', [$this, 'datetime']),
            new TwigFilter('year', [$this, 'year']),
            new TwigFilter('RFC822', [$this, 'RFC822']),
        ];
    }

    public function date(?DateTime $date = null, $language = 'is', $year = true)
    {
        if (!$date) return '';
        $currentLocale = setlocale(LC_ALL, 0);
        setlocale(LC_TIME, $language == 'is' ? 'is_IS.utf8' : 'en_GB.utf8');
        $string = strftime('%e.%B %Y', $date->getTimestamp());
        setlocale(LC_TIME, $currentLocale);
        return $string;
    }

    public function datetime(DateTime $date, $language = 'is', $year = true)
    {
        return $date->format('Y m d H:m');
    }

    public function year(DateTime $date): string
    {
        return $date->format('Y');
    }

    public function RFC822(DateTime $date): string
    {
        return $date->format('r');
    }
}
