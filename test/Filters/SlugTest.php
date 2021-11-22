<?php

namespace App\Filters;

use PHPUnit\Framework\TestCase;
use App\Filters\Slug;

class SlugTest extends TestCase
{
    public function testGetFilters()
    {
        $actual = (new Slug())->getFilters();

        $this->assertEquals('slug', $actual[0]->getName());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSlug($input, $id, $expected)
    {
        $actual = (new Slug())->slug($input, $id);

        $this->assertEquals($expected, $actual);
    }

    public function dataProvider()
    {
        return [
            ['one', 3, 'one-3'],
            ['ONE', 3, 'one-3'],
            ['o?e', 3, 'o-e-3'],
            ['?', 3, 'n-a-3'],
        ];
    }
}
