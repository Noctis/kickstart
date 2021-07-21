<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\AbstractAction;
use Psr\Http\Server\MiddlewareInterface;

interface RouteHandlerInfoInterface
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
