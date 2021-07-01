<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

interface RouteHandlerInfoInterface
{
    /**
     * @return class-string<AbstractAction>
     */
    public function getActionClassName(): string;

    /**
     * @return list<class-string<AbstractMiddleware>>
     */
    public function getGuardNames(): array;
}