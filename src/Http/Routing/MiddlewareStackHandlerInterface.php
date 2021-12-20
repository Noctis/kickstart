<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Routing\MiddlewareStackInterface;

interface MiddlewareStackHandlerInterface
{
    public function setMiddlewareStack(MiddlewareStackInterface $middlewareStack): void;
}
