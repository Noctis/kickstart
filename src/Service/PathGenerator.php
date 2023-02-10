<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Noctis\KickStart\Exception\RouteNotFoundException;
use Noctis\KickStart\Http\Routing\RoutesCollection;
use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;
use Noctis\KickStart\ValueObject\GeneratedUriInterface;

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
    public function toRoute(string $routeName, array $params = []): GeneratedUriInterface
    {
        /** @psalm-suppress PossiblyNullReference */
        $route = $this->routes
            ->getNamedRoute($routeName);
        if ($route === null) {
            throw new RouteNotFoundException($routeName);
        }

        return $this->urlGenerator
            ->generate(
                $route->getPath(),
                $params
            );
    }
}
