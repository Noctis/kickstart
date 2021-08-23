<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Server\MiddlewareInterface;

interface RouteHandlerInfoInterface
{
    /**
     * @return class-string<ActionInterface>
     */
    public function getActionClassName(): string;

    /**
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getMiddlewareNames(): array;
}
