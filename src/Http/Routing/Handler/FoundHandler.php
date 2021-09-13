<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Routing\RequestHandler;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\FoundRouteInfo;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

final class FoundHandler implements FoundHandlerInterface
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(array $routeInfo): ResponseInterface
    {
        $routeInfo = FoundRouteInfo::createFromArray($routeInfo);
        $routeHandlerDefinition = $routeInfo->getRouteHandlerDefinition();
        $requestHandler = new RequestHandler(
            $this->container,
            $this->getAction(
                $routeHandlerDefinition->getActionClassName()
            ),
            $this->getGuards(
                $routeHandlerDefinition->getGuardNames()
            )
        );

        return $requestHandler->handle(
            $this->getRequest(
                $routeInfo->getRequestVars()
            )
        );
    }

    /**
     * @param class-string<AbstractAction> $actionClassName
     */
    private function getAction(string $actionClassName): AbstractAction
    {
        /** @var AbstractAction */
        return $this->container
            ->get($actionClassName);
    }

    /**
     * @param list<class-string<MiddlewareInterface>> $guardsNames
     *
     * @return list<MiddlewareInterface>
     */
    private function getGuards(array $guardsNames): array
    {
        return array_map(
            function (string $guardClassName): MiddlewareInterface {
                /** @var MiddlewareInterface */
                return $this->container
                    ->get($guardClassName);
            },
            $guardsNames
        );
    }

    /**
     * @param array<string, string> $requestVars
     */
    private function getRequest(array $requestVars): ServerRequestInterface
    {
        $request = $this->container
            ->get(ServerRequestInterface::class);

        foreach ($requestVars as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $request;
    }
}
