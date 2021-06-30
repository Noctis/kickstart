<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

interface RoutesParserInterface
{
    /**
     * @param list<array> $routes
     *
     * @return list<RouteDefinitionInterface>
     */
    public function parse(array $routes): array;
}
