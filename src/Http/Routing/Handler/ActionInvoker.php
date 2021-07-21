<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

final class ActionInvoker implements ActionInvokerInterface
{
    private Container $container;

    /**
     * @psalm-suppress DeprecatedClass
     */
    private ?AbstractAction $action;

    /** @var list<MiddlewareInterface> */
    private array $guards;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->action = null;
        $this->guards = [];
    }

    /**
     * @inheritDoc
     */
    public function setAction(AbstractAction $action): self
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
            $this->container
                ->set(ServerRequestInterface::class, $request);

            /**
             * @psalm-suppress UndefinedMethod
             * @var ResponseInterface
             */
            return $this->container
                ->call([$this->action, 'execute']);
        }

        $guard = array_shift($this->guards);

        return $guard->process($request, $this);
    }
}
