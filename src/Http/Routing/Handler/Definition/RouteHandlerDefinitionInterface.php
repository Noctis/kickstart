<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Psr\Http\Server\MiddlewareInterface;

interface RouteHandlerDefinitionInterface
{
    /**
     * @return class-string<AbstractAction>
     * @psalm-suppress DeprecatedClass
     */
    public function getActionClassName(): string;

    /**
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getGuardNames(): array;
}
