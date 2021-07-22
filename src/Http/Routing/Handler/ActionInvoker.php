<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ActionInvoker implements ActionInvokerInterface
{
    private ?ActionInterface $action = null;

    /** @var list<AbstractMiddleware> */
    private array $guards = [];

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
