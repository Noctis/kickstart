<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

interface RouteDefinitionInterface
{
    public function getMethod(): string;

    public function getPath(): string;

    /**
     * @return class-string<AbstractAction>
     */
    public function getAction(): string;

    /**
     * @return list<class-string<AbstractMiddleware>>
     */
    public function getGuards(): array;
}
