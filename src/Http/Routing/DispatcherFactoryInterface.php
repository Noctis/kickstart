<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;

interface DispatcherFactoryInterface
{
    /**
     * @param list<RouteInterface> $routes
     */
    public function createFromArray(array $routes): Dispatcher;
}
