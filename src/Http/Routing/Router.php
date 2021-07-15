<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;

final class Router implements RouterInterface
{
    private RoutesParserInterface $routesParser;
    private DispatcherFactoryInterface $dispatcherFactory;

    /** @var list<array> */
    private array $routes;

    /**
     * @param list<array> $routes
     */
    public function __construct(
        RoutesParserInterface $routesParser,
        DispatcherFactoryInterface $dispatcherFactory,
        array $routes
    ) {
        $this->routesParser = $routesParser;
        $this->dispatcherFactory = $dispatcherFactory;
        $this->routes = $routes;
    }

    /**
     * @inheritDoc
     */
    public function getDispatchInfo(ServerRequestInterface $request): array
    {
        $dispatcher = $this->dispatcherFactory
            ->createFromArray(
                $this->routesParser
                    ->parse($this->routes)
            );

        return $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()
                ->getPath()
        );
    }
}
