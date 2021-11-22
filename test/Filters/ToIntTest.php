<?php

namespace App\Filters;

use PHPUnit\Framework\TestCase;

class ToIntTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testResult($input, $expected)
    {
        $actual = (new ToInt())->filter($input);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider()
    {
        return [
            ['1', 1],
            ['1.2', 1],
            ['one', null],
            ['A1', null],
        ];
    }
}
