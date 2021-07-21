<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Psr\Http\Server\RequestHandlerInterface;

interface ActionInvokerInterface extends RequestHandlerInterface
{
    /**
     * @param AbstractAction $action
     */
    public function setAction(AbstractAction $action): self;

    /**
     * @param list<AbstractMiddleware> $guards
     */
    public function setGuards(array $guards): self;
}
