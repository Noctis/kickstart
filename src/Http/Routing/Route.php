<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Server\MiddlewareInterface;

final class Route implements RouteInterface
{
    private string $method;
    private string $path;

    /** @var class-string<ActionInterface> */
    private string $action;

    /** @var list<class-string<MiddlewareInterface>> */
    private array $middlewareNames;

    /**
     * @param class-string<ActionInterface>           $action
     * @param list<class-string<MiddlewareInterface>> $middlewareNames
     */
    public function __construct(string $method, string $path, string $action, array $middlewareNames = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
        $this->middlewareNames = $middlewareNames;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewareNames(): array
    {
        return $this->middlewareNames;
    }
}
