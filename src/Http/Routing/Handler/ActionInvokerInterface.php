<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Psr\Http\Server\RequestHandlerInterface;

interface ActionInvokerInterface extends RequestHandlerInterface
{
    public function setAction(ActionInterface $action): self;

    /**
     * @param list<AbstractMiddleware> $guards
     */
    public function setGuards(array $guards): self;
}
