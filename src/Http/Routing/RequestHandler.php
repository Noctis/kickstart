<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RequestHandler implements RequestHandlerInterface
{
    private Container $container;
    private AbstractAction $action;

    /** @var list<MiddlewareInterface> */
    private array $guards;

    /**
     * @param list<MiddlewareInterface> $guards
     */
    public function __construct(Container $container, AbstractAction $action, array $guards)
    {
        $this->container = $container;
        $this->action = $action;
        $this->guards = $guards;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->guards)) {
            $this->container
                ->set(ServerRequestInterface::class, $request);

            /**
             * @psalm-suppress InvalidArgument
             * @var ResponseInterface
             */
            return $this->container
                ->call([$this->action, 'execute']);
        }

        $guard = array_shift($this->guards);

        return $guard->process($request, $this);
    }
}
