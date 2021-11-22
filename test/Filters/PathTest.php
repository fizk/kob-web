<?php

namespace App\Filters;

use Psr\Http\Message\ServerRequestInterface as Request;
use PHPUnit\Framework\TestCase;
use App\Router\RouterInterface;
use App\Router\Route;
use App\Filters\Path;
use InvalidArgumentException;

class PathTest extends TestCase
{
    public function testPath()
    {
        $routerCollection = new class implements RouterInterface {
            public function addRoute(Route $route): void {}

            public function match(Request $request): ?Route {
                return null;
            }

            public function generateUri(string $name, array $substitutions = [], array $options = []): string {
                return 'url';
            }
        };
        $expected = 'url';
        $actual = (new Path($routerCollection))->path('', []);

        $this->assertEquals($expected, $actual);
    }

    public function testPathException()
    {
        $this->expectException(InvalidArgumentException::class);

        $routerCollection = new class implements RouterInterface {
            public function addRoute(Route $route): void {}

            public function match(Request $request): ?Route {
                return null;
            }

            public function generateUri(string $name, array $substitutions = [], array $options = []): string {
                throw new InvalidArgumentException();
            }
        };

        (new Path($routerCollection))->path('', []);
    }

    /**
     * @dataProvider selectPathProvider
     */
    public function testSelectPath($selection, $condition, $expected)
    {
        $routerCollection = new class implements RouterInterface {
            public function addRoute(Route $route): void {}

            public function match(Request $request): ?Route {
                return null;
            }

            public function generateUri(string $name, array $substitutions = [], array $options = []): string {
                return $name;
            }
        };

        $actual = (new Path($routerCollection))->selectPath($selection, $condition, []);

        $this->assertEquals($expected, $actual);
    }

    public function selectPathProvider()
    {
        return [
            [['one', 'two'], true, 'one'],
            [['one', 'two'], false, 'two'],
        ];
    }

    public function testSelectPathException()
    {
        $this->expectException(InvalidArgumentException::class);

        $routerCollection = new class implements RouterInterface
        {
            public function addRoute(Route $route): void
            {
            }

            public function match(Request $request): ?Route
            {
                return null;
            }

            public function generateUri(string $name, array $substitutions = [], array $options = []): string
            {
                throw new InvalidArgumentException();
            }
        };

        (new Path($routerCollection))->selectPath(['one', 'two'], true, []);
    }

    public function testGetFunction()
    {
        $routerCollection = new class implements RouterInterface
        {
            public function addRoute(Route $route): void
            {
            }

            public function match(Request $request): ?Route
            {
                return null;
            }

            public function generateUri(string $name, array $substitutions = [], array $options = []): string
            {
                return 'url';
            }
        };

        $actual = (new Path($routerCollection))->getFunctions();

        $this->assertEquals('path', $actual[0]->getName());
        $this->assertEquals('selectPath', $actual[1]->getName());
    }
}
