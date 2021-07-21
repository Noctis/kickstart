<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Noctis\KickStart\Http\Action\AbstractAction;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface ActionInvokerInterface extends RequestHandlerInterface
{
    /**
     * @param AbstractAction $action
     * @psalm-suppress DeprecatedClass
     */
    public function setAction(AbstractAction $action): self;

    /**
     * @param list<MiddlewareInterface> $guards
     */
    public function setGuards(array $guards): self;
}
