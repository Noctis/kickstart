<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;

interface DispatcherFactoryInterface
{
    /**
     * @param list<RouteDefinitionInterface> $routeDefinitions
     */
    public function createFromArray(array $routeDefinitions): Dispatcher;
}
