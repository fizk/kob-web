<?php
namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ImageUpdatePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\ImageUpdatePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Image::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
