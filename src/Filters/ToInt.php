<?php

namespace App\Filters;

use Laminas\Filter\AbstractFilter;

class ToInt extends AbstractFilter
{
    /**
     * @param  mixed $value
     * @return int|mixed
     */
    public function filter($value)
    {
        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }
}
