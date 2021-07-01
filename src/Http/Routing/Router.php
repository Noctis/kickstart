<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;

use function FastRoute\simpleDispatcher;

final class Router implements RouterInterface
{
    private RoutesParserInterface $routesParser;
    private RoutesLoaderInterface $routesLoader;

    /** @var list<array> */
    private array $routes = [];

    public function __construct(RoutesParserInterface $routesParser, RoutesLoaderInterface $routesLoader)
    {
        $this->routesParser = $routesParser;
        $this->routesLoader = $routesLoader;
    }

    /**
     * @inheritDoc
     */
    public function setRoutes(array $routes): RouterInterface
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDispatchInfo(ServerRequestInterface $request): array
    {
        return $this->getDispatcher()
            ->dispatch(
                $request->getMethod(),
                $request->getUri()
                    ->getPath()
            );
    }

    private function getDispatcher(): Dispatcher
    {
        return simpleDispatcher(
            $this->routesLoader
                ->load(
                    $this->routesParser
                        ->parse($this->routes)
                )
        );
    }
}
