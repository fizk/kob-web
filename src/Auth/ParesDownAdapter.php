<?php
namespace App\Auth;

use Parsedown;
use Aptoma\Twig\Extension\MarkdownEngineInterface;

class ParesDownAdapter implements MarkdownEngineInterface
{
    public function transform($content)
    {
        return Parsedown::instance()
            ->setSafeMode(false)
            ->setMarkupEscaped(false)
            ->text($content);
    }
    public function getName()
    {
        return 'erusev/parsedown';
    }
}
