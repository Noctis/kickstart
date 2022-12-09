<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Psl\Iter\Iterator;
use Traversable;

final class RoutesCollection implements RoutesCollectionInterface
{
    /** @var array<int|string, RouteInterface> */
    private array $routes = [];

    /**
     * @param array<int|string, RouteInterface> $routes
     */
    public function __construct(array $routes)
    {
        foreach ($routes as $key => $route) {
            $this->addRoute($key, $route);
        }
    }

    public function addRoute(int|string $key, RouteInterface $route): RoutesCollectionInterface
    {
        $this->routes[$key] = $route;

        return $this;
    }

    public function getIterator(): Traversable
    {
        return Iterator::create($this->routes);
    }
}
