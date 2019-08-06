<?php
namespace App\Factory;

use App\Service;
use App\Handler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class SearchPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\SearchPageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Search::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
