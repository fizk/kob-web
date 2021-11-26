<?php

namespace App\Filters;

use Laminas\Filter\AbstractFilter;

class ArrayFilter extends AbstractFilter
{
    /**
     * @param  mixed $value
     * @return int|mixed
     */
    public function filter($value)
    {
        if (!is_array($value)) {
            return [];
        }

        return $value;
    }
}
