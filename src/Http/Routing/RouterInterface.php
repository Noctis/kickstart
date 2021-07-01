<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): self;

    /**
     * @return array
     */
    public function getDispatchInfo(ServerRequestInterface $request): array;
}
