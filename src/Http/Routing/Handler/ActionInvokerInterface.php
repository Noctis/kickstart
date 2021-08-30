<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface ActionInvokerInterface extends RequestHandlerInterface
{
    /**
     * @param array<class-string<MiddlewareInterface>> $stack
     */
    public function setStack(array $stack): void;
}
