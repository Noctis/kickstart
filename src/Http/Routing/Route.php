<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

final class Route implements RouteInterface
{
    private string $method;
    private string $path;

    /** @var class-string<AbstractAction> */
    private string $action;

    /** @var list<class-string<AbstractMiddleware>> */
    private array $guards;

    /**
     * @param class-string<AbstractAction>           $action
     * @param list<class-string<AbstractMiddleware>> $guards
     */
    public function __construct(string $method, string $path, string $action, array $guards = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->action = $action;
        $this->guards = $guards;
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
    public function getGuards(): array
    {
        return $this->guards;
    }
}
