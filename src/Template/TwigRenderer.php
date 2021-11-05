<?php

namespace App\Template;

use Twig\Loader\FilesystemLoader;
use Twig\Extension\AbstractExtension;
use Twig\Environment;

/**
 * Interface defining required template capabilities.
 */
class TwigRenderer implements TemplateRendererInterface
{
    private FilesystemLoader $loader;
    private Environment $twig;

    public function __construct(string $path, bool $debug = false)
    {
        $this->loader = new FilesystemLoader($path);
        $this->twig = new Environment($this->loader, ['debug' => $debug]);

        if ($debug) {
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        }
    }

    public function render(string $name, $params = []) : string
    {
        // $template = preg_replace('#^([^:]+)::(.*)$#', '@$1/$2', $template);
        // if (!preg_match('#\.[a-z]+$#i', $template)) {
        //     return sprintf('%s.%s', $template, $this->suffix);
        // }
        return $this->twig->render(str_replace('::', '/', $name) . '.html.twig', $params);
    }

    public function addPath(string $path, string $namespace = null): self
    {
        $this->loader->addPath($path, $namespace);
        return $this;
    }

    public function getPaths() : array
    {
        return $this->loader->getPaths();
    }

    /**
     * @todo
     */
    public function addDefaultParam(string $templateName, string $param, $value) : self
    {
        $this->twig->addGlobal($param, $value);
        return $this;
    }

    public function addExtension(AbstractExtension $ext): self
    {
        $this->twig->addExtension($ext);
        return $this;
    }
}
