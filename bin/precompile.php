<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use Laminas\ServiceManager\ServiceManager;
use App\Template\TemplateRendererInterface;

$serviceManager = new ServiceManager(require './config/service.php');

$twig = $serviceManager->get(TemplateRendererInterface::class);

while (($fn = fgets(STDIN))) {
    $fn = trim($fn);
    $twig->loadTemplate($fn);
}
