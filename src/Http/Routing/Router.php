<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;

final class Router implements RouterInterface
{
    private DispatcherFactoryInterface $dispatcherFactory;

    /** @var list<RouteInterface> */
    private array $routes;

    /**
     * @param list<RouteInterface> $routes
     */
    public function __construct(
        DispatcherFactoryInterface $dispatcherFactory,
        array $routes
    ) {
        $this->dispatcherFactory = $dispatcherFactory;
        $this->routes = $routes;
    }

    /**
     * @inheritDoc
     */
    public function getDispatchInfo(ServerRequestInterface $request): array
    {
        $dispatcher = $this->dispatcherFactory
            ->createFromArray($this->routes);

        return $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()
                ->getPath()
        );
    }
}
