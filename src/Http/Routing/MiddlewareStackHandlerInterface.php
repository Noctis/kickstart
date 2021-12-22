<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareStackHandlerInterface extends RequestHandlerInterface
{
    public function setMiddlewareStack(MiddlewareStackInterface $middlewareStack): void;
}
