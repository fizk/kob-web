<?php

use PHPUnit\Framework\TestCase;

use App\Service\Asset;

class AssetsTest extends TestCase
{
    /**
     * @dataProvider filenameProvider
     */
    public function testName(string $in, string $ext, string $out)
    {
        $this->assertEquals($out, Asset::fileName($in, $ext, ''));
    }

    public function filenameProvider()
    {
        return [
            ['name.png', 'jpg', 'name.jpg'],
            ['NamE.png', 'jpg', 'name.jpg'],
            ['this name.png', 'jpg', 'this-name.jpg'],
            ['á sjó.png', 'jpg', 'a-sjo.jpg'],
            ['á sjó?vei=5.png', 'jpg', 'a-sjo-vei-5.jpg'],
        ];
    }
}
