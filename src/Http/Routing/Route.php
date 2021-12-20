<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Fig\Http\Message\RequestMethodInterface;
use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @psalm-immutable
 */
final class Route implements RouteInterface
{
    private string $method;
    private string $path;

    /** @var class-string<ActionInterface> */
    private string $actionName;

    /** @var list<class-string<MiddlewareInterface>> */
    private array $middlewareNames;

    /** @var array<string, string> */
    private array $additionalVars = [];

    /**
     * @param class-string<ActionInterface>           $actionName
     * @param list<class-string<MiddlewareInterface>> $middlewareNames
     */
    public static function get(string $path, string $actionName, array $middlewareNames = []): self
    {
        return new self(RequestMethodInterface::METHOD_GET, $path, $actionName, $middlewareNames);
    }

    /**
     * @param class-string<ActionInterface>           $actionName
     * @param list<class-string<MiddlewareInterface>> $middlewareNames
     */
    public static function post(string $path, string $actionName, array $middlewareNames = []): self
    {
        return new self(RequestMethodInterface::METHOD_POST, $path, $actionName, $middlewareNames);
    }

    /**
     * @param class-string<ActionInterface>           $actionName
     * @param list<class-string<MiddlewareInterface>> $middlewareNames
     * @param array<string, string>                   $additionalVars
     */
    public function __construct(
        string $method,
        string $path,
        string $actionName,
        array $middlewareNames = [],
        array $additionalVars = []
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->actionName = $actionName;
        $this->middlewareNames = $middlewareNames;
        $this->additionalVars = $additionalVars;
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
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewareNames(): array
    {
        return $this->middlewareNames;
    }

    /**
     * @inheritDoc
     */
    public function withAdditionalVars(array $vars): RouteInterface
    {
        return new self($this->method, $this->path, $this->actionName, $this->middlewareNames, $vars);
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalVars(): array
    {
        return $this->additionalVars;
    }
}
