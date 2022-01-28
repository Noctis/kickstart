<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Server\MiddlewareInterface;

interface RouteInterface
{
    public function getMethod(): string;

    public function getPath(): string;

    /**
     * @return class-string<ActionInterface>
     */
    public function getActionName(): string;

    /**
     * @return list<class-string<MiddlewareInterface>>
     */
    public function getMiddlewareNames(): array;

    /**
     * @param array<string, string> $vars
     *
     * @return static
     */
    public function withAdditionalVars(array $vars): self;

    /**
     * @return array<string, string>
     */
    public function getAdditionalVars(): array;
}
