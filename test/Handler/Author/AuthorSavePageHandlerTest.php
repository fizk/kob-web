<?php

namespace App\Handler\Author;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{HtmlResponse, RedirectResponse, JsonResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Dom\Query;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Router\RouterInterface;
use App\Service;
use App\Middleware;
use App\Model;

use function App\Router\dispatch;

class AuthorSavePageHandlerTest extends TestCase
{
    public function testSaveAndRedirect()
    {
        $request = (new ServerRequest())
            ->withParsedBody(['name' => 'author'])
            ->withUri(new Uri('/update/author/1'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
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
        $serviceManager->setFactory(Service\Author::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function save(Model\Author $data): int
                {
                    return 1;
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/listamenn/1'], $response->getHeader('location'));
    }

    public function testSaveAndRedirectError()
    {
        $request = (new ServerRequest())
            ->withParsedBody([])
            ->withUri(new Uri('/update/author/1'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
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
        $serviceManager->setFactory(Service\AuthorService::class, function () {
            return new class extends Service\AbstractAuthor
            {
                public function save(Model\Author $data): int
                {
                    return 1;
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
