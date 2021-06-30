<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

interface RoutesLoaderInterface
{
    /**
     * @param list<RouteDefinitionInterface> $routes
     */
    public function load(array $routes): callable;
}
