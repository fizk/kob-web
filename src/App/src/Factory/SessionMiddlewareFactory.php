<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Expressive\Template\TemplateRendererInterface;
use App\Middleware;

class SessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : MiddlewareInterface
    {
        $template = $container->get(TemplateRendererInterface::class);
        $authentication = $container->get(AuthenticationService::class);

        return new Middleware\SessionMiddleware($template, $authentication);
    }
}
