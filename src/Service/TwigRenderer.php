<?php declare(strict_types=1);
namespace Noctis\KickStart\Service;

use Twig\Environment as Twig;

final class TwigRenderer implements TemplateRendererInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function render(string $template, array $params = []): string
    {
        return $this->twig
            ->render($template, $params);
    }
}