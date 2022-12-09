<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Twig\Extension;

use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;
use Noctis\KickStart\Service\UrlGeneratorInterface;
use RuntimeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UrlExtension extends AbstractExtension
{
    private ?RoutesCollectionInterface $routes = null;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /** (this method will be called by the DIC) */
    public function setRoutes(RoutesCollectionInterface $routes): void
    {
        $this->routes = $routes;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'path',
                $this->getPath(...)
            ),
        ];
    }

    /**
     * @param array<string, string|int> $params
     */
    private function getPath(string $routeName, array $params): string
    {
        if ($this->routes === null) {
            throw new RuntimeException('Routes list has not been set. Did you forget to call setRoutes()?');
        }

        /** @psalm-suppress PossiblyNullReference */
        $route = $this->routes
            ->getNamedRoute($routeName);
        $path = $route !== null
            ? $route->getPath()
            : $routeName;

        return $this->urlGenerator
            ->generate($path, $params);
    }
}
