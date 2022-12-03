<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Twig\Environment as Twig;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

final class TwigRenderer implements TemplateRendererInterface
{
    public function __construct(
        private readonly Twig $twig
    ) {
    }

    public function render(string $template, array $params = []): string
    {
        return $this->twig
            ->render($template, $params);
    }

    public function registerFunction(string $name, callable $function): void
    {
        $this->twig
            ->addFunction(
                new TwigFunction($name, $function)
            );
    }

    public function registerExtension(ExtensionInterface $extension): void
    {
        $this->twig
            ->addExtension($extension);
    }
}
