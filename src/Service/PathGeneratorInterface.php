<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Noctis\KickStart\Exception\RouteNotFoundException;
use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;
use Noctis\KickStart\ValueObject\GeneratedUriInterface;

interface PathGeneratorInterface
{
    public function setRoutes(RoutesCollectionInterface $routes): self;

    /**
     * @param array<string, string|int> $params
     *
     * @throws RouteNotFoundException
     */
    public function toRoute(string $routeName, array $params = []): GeneratedUriInterface;
}
