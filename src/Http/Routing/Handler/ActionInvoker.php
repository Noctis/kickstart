<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ActionInvoker implements ActionInvokerInterface
{
    private Container $container;
    private ?ActionInterface $action;

    /** @var list<AbstractMiddleware> */
    private array $guards;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->action = null;
        $this->guards = [];
    }

    public function setAction(ActionInterface $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setGuards(array $guards): self
    {
        $this->guards = $guards;

        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->container
            ->set(ServerRequestInterface::class, $request);

        if (empty($this->guards)) {
            /**
             * @psalm-suppress PossiblyNullReference
             * @var ResponseInterface
             */
            return $this->action
                ->process($request, $this);
        }

        $guard = array_shift($this->guards);

        return $guard->process($request, $this);
    }
}
