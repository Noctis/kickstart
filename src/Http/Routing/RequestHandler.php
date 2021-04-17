<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestHandler implements RequestHandlerInterface
{
    private Container $container;
    private AbstractAction $action;

    /** @var list<AbstractMiddleware> */
    private array $guards;

    /**
     * @param list<AbstractMiddleware> $guards
     */
    public function __construct(Container $container, AbstractAction $action, array $guards)
    {
        $this->container = $container;
        $this->action = $action;
        $this->guards = $guards;
    }

    public function handle(Request $request): Response
    {
        if (empty($this->guards)) {
            /**
             * @psalm-suppress InvalidArgument
             * @var Response
             */
            return $this->container
                ->call([$this->action, 'execute']);
        }

        $guard = array_shift($this->guards);

        return $guard->process($request, $this);
    }
}
