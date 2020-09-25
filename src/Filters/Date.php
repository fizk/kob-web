<?php
namespace App\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Date extends AbstractExtension
{
    private array $monthIs = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Maí',
        'Jún',
        'Júl',
        'Ágú',
        'Sep',
        'Okt',
        'Nóv',
        'Des',
    ];

    private array $monthEn = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
    ];

    public function getFilters()
    {
        return [
            new TwigFilter('date', [$this, 'date']),
        ];
    }

    public function date($date, $language = 'is', $year = true)
    {
        try {
            $dateString = $year
                ? (new \DateTime($date))->format('d. M Y')
                : (new \DateTime($date))->format('d. M');
            if ($language === 'is') {
                $dateString = str_replace($this->monthEn, $this->monthIs, $dateString);
            }

            return $dateString;
        } catch (\Exception $e) {
            return '';
        }
    }
}
