<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

interface RoutesParserInterface
{
    /**
     * @param list<array> $routes
     *
     * @return list<RouteInterface>
     */
    public function parse(array $routes): array;
}
