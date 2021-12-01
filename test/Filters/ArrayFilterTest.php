<?php

namespace App\Filters;

use PHPUnit\Framework\TestCase;
use App\Filters\ArrayFilter;

class ArrayFilterTest extends TestCase
{
    public function testArray()
    {
        $expected = [];
        $actual = (new ArrayFilter)->filter([]);

        $this->assertEquals($expected, $actual);
    }

    public function testString()
    {
        $expected = [];
        $actual = (new ArrayFilter)->filter('');

        $this->assertEquals($expected, $actual);
    }
}
