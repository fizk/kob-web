<?php
namespace App\Factory;

use App\Middleware;
use Psr\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Template\TemplateRendererInterface;

class SessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Middleware\SessionMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get(AuthenticationService::class)
        );
    }
}
