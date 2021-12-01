<?php

namespace App\Handler\Login;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{RedirectResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Authentication\Result;
use App\Router\RouterInterface;
use function App\Router\dispatch;

class LogoutSubmitPageHandlerTest extends TestCase
{
    public function testLoginSuccess()
    {
        $request = (new ServerRequest())
            ->withUri(new Uri('/logout'));

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(AuthenticationServiceInterface::class, function () {
            return new class implements AuthenticationServiceInterface
            {
                public function authenticate() {
                    return new Result(Result::SUCCESS, []);
                }
                public function hasIdentity()
                {
                    return true;
                }
                public function getIdentity()
                {
                    return [];
                }
                public function clearIdentity()
                {
                }
            };
        });

        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/home'], $response->getHeader('Location'));
    }
}
