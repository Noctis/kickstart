<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

interface RouterInterface
{
    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): void;

    public function route(): void;
}
