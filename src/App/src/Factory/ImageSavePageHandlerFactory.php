<?php
/**
 * Created by PhpStorm.
 * User: einar.adalsteinsson
 * Date: 7/8/19
 * Time: 12:52 AM
 */

namespace App\Factory;

use App\Handler;
use App\Service;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ImageSavePageHandlerFactory
{
    public function __invoke(ContainerInterface $container) {
        return new Handler\ImageSavePageHandler(
            $container->get(RouterInterface::class),
            $container->get(Service\Image::class),
            $container->get(TemplateRendererInterface::class)
        );
    }
}
