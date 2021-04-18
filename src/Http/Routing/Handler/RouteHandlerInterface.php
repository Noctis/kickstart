<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Psr\Http\Message\ResponseInterface;

interface RouteHandlerInterface
{
    public function handle(array $routeInfo): ResponseInterface;
}
