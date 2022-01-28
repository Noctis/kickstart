<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use ArrayIterator;

final class RoutesCollection implements RoutesCollectionInterface
{
    /** @var list<RouteInterface> */
    private array $routes = [];

    /**
     * @param list<RouteInterface> $routes
     */
    public function __construct(array $routes)
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }

    public function addRoute(RouteInterface $route): RoutesCollectionInterface
    {
        $this->routes[] = $route;

        return $this;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->routes);
    }
}
