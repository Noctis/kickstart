<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Noctis\KickStart\Http\Routing\RoutesCollection;
use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;

final class PathGenerator implements PathGeneratorInterface
{
    private RoutesCollectionInterface $routes;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
        $this->routes = new RoutesCollection([]);
    }

    public function setRoutes(RoutesCollectionInterface $routes): PathGeneratorInterface
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function generate(string $routeName, array $params = []): string
    {
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
