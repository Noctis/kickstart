<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Twig\Extension;

use Noctis\KickStart\Service\PathGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function Psl\Regex\replace;

final class UrlExtension extends AbstractExtension
{
    public function __construct(
        private readonly PathGeneratorInterface $pathGenerator
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'route',
                $this->getRoute(...)
            ),
        ];
    }

    /**
     * @param array<string, string|int> $params
     */
    private function getRoute(string $routeName, array $params = []): string
    {
        $path = $this->pathGenerator
            ->toRoute($routeName, $params)
            ->toString();

        if ($path !== '/') {
            $path = replace($path, '/^\/+/', '');
        }

        return $path;
    }
}
