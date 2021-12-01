<?php

namespace App\Handler\Login;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\Response\{RedirectResponse};
use Laminas\Diactoros\ServerRequest;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Diactoros\Uri;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Authentication\Result;
use Laminas\Authentication\Adapter\AdapterInterface;
use App\Router\RouterInterface;
use App\Auth\PasswordAuthAdapter;
use function App\Router\dispatch;

class LoginSubmitPageHandlerTest extends TestCase
{
    public function testLoginSuccess()
    {
        $request = (new ServerRequest())
            ->withParsedBody(['email' => '', 'password' => ''])
            ->withUri(new Uri('/login'))
            ->withMethod('POST');

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
                public function setAdapter($adapter)
                {
                    return $this;
                }
            };
        });
        $serviceManager->setFactory(AdapterInterface::class, function () {
            return new class extends PasswordAuthAdapter{
                public function __construct()
                {

                }

                public function authenticate()
                {
                    return new Result(Result::SUCCESS, []);
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/update'], $response->getHeader('Location'));
    }

    public function testLoginFail()
    {
        $request = (new ServerRequest())
            ->withParsedBody(['email' => '', 'password' => ''])
            ->withUri(new Uri('/login'))
            ->withMethod('POST');

        $serviceManager = new ServiceManager(require './config/service.php');
        $serviceManager->setAllowOverride(true);
        $serviceManager->setFactory(AuthenticationServiceInterface::class, function () {
            return new class implements AuthenticationServiceInterface
            {
                public function authenticate() {
                    return new Result(Result::FAILURE_CREDENTIAL_INVALID, []);
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
                public function setAdapter($adapter)
                {
                    return $this;
                }
            };
        });
        $serviceManager->setFactory(AdapterInterface::class, function () {
            return new class extends PasswordAuthAdapter{
                public function __construct()
                {

                }

                public function authenticate()
                {
                    return new Result(Result::FAILURE_CREDENTIAL_INVALID, []);
                }
            };
        });
        $collection = $serviceManager->get(RouterInterface::class);
        $collection->setRouteConfig(require './config/router.php');
        $response = dispatch($request, $collection, $serviceManager);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['/login'], $response->getHeader('Location'));
    }
}
