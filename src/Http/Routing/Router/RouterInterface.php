<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Router;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    public function addRoute(RouteInterface $route): self;

    public function route(ServerRequestInterface $request): RouteInterface;
}
