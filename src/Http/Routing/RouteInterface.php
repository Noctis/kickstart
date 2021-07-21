<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\AbstractAction;
use Psr\Http\Server\MiddlewareInterface;

interface RouteInterface
{
    public function getMethod(): string;

    public function getPath(): string;

    /**
     * @return class-string<AbstractAction>
     * @psalm-suppress DeprecatedClass
     */
    public function getAction(): string;

    /**
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getGuards(): array;
}
