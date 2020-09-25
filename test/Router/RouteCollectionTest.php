<?php

namespace App\Router;

use PHPUnit\Framework\TestCase;
use stdClass;

class RouteCollectionTest extends TestCase
{
    public function testGenerateUri()
    {
        $router = new RouteCollection();
        $router->addRoute(new Route('GET', '/album/{id}', stdClass::class, 'album'));
        $router->addRoute(new Route('GET', '/album/{id}/band', stdClass::class, 'band'));
        $router->addRoute(new Route('GET', '/album/{id}/band/{band_id}', stdClass::class, 'band-id'));

        $expected = '/album/1';
        $actual = $router->generateUri('album', ['id' => 1]);
        $this->assertEquals($expected, $actual);

        $expected = '/album/10/band';
        $actual = $router->generateUri('band', ['id' => 10]);
        $this->assertEquals($expected, $actual);

        $expected = '/album/10/band/name';
        $actual = $router->generateUri('band-id', ['band_id' => 'name', 'id' => 10]);
        $this->assertEquals($expected, $actual);
    }
}
