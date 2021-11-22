<?php

namespace App\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{RedirectResponse, HtmlResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use Laminas\Dom\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Router\RouterInterface;
use App\Middleware;
use App\Service;
use App\Model;
use function App\Router\dispatch;

class EntrySavePageHandlerTest extends TestCase
{
    public function testSaveAndRedirect()
    {
        $request = (new ServerRequest())
            ->withParsedBody([
                'title' => 'Some title',
                'from' => '2001-01-01',
                'to' => '2001-01-01',
                'type' => 'show',
                'orientation' => 'landscape',
            ])
            ->withUri(new Uri('/update/entry'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setFactory(Middleware\AdminMenuMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            };
        });
        $serviceManager->setFactory(Middleware\AuthenticationMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            };
        });
        $serviceManager->setFactory(Service\Entry::class, function () {
            return new class extends Service\AbstractEntry{
                public function save(array $data): int
                {
                    return 2;
                }
                public function get(string $id): ?Model\Entry
                {
                    return new Model\Entry();
                }
            };
        });
        $serviceManager->setFactory(Service\Search::class, function () {
            return new class extends Service\AbstractSearch {
                public function save($item): bool
                {
                    return true;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/shows/2'], $response->getHeader('location'));
    }


    public function testSaveError()
    {
        $request = (new ServerRequest())
            ->withParsedBody([
                'title' => null,
                'from' => '2001-01-01',
                'to' => '2001-01-01',
                'type' => 'show',
                'orientation' => 'landscape',
            ])
            ->withUri(new Uri('/update/entry'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setFactory(Middleware\AdminMenuMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            };
        });
        $serviceManager->setFactory(Middleware\AuthenticationMiddleware::class, function () {
            return new class implements MiddlewareInterface
            {
                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $handler->handle($request);
                }
            };
        });
        $serviceManager->setFactory(Service\Entry::class, function () {
            return new class extends Service\AbstractEntry
            {
                public function save(array $data): int
                {
                    return 2;
                }
                public function get(string $id): ?Model\Entry
                {
                    return new Model\Entry();
                }
            };
        });
        $serviceManager->setFactory(Service\Search::class, function () {
            return new class extends Service\AbstractSearch
            {
                public function save($item): bool
                {
                    return true;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertCount(1, (new Query($response->getBody()->__toString()))->execute('.form-error'));
    }
}
