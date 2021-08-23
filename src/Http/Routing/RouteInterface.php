<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

interface RouteInterface
{
    public function getMethod(): string;

    public function getPath(): string;

    /**
     * @return class-string<ActionInterface>
     */
    public function getAction(): string;

    /**
     * @return list<class-string<AbstractMiddleware>>
     */
    public function getGuards(): array;
}
