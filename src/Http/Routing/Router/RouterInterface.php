<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Router;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    public function setRoutes(RoutesCollectionInterface $routesCollection): self;

    public function route(ServerRequestInterface $request): RouteInterface;
}
