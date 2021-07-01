<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\FoundRouteInfo;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        $this->container
            ->set(
                'request.vars',
                $routeInfo->getRequestVars()
            );

        $routeHandlerInfo = $routeInfo->getRouteHandlerInfo();
        $actionInvoker = new ActionInvoker($this->container);
        $actionInvoker = $actionInvoker
            ->setAction(
                $this->getAction(
                    $routeHandlerInfo->getActionClassName()
                )
            )
            ->setGuards(
                $this->getGuards(
                    $routeHandlerInfo->getGuardNames()
                )
            );

        return $actionInvoker->handle(
            $this->container
                ->get(ServerRequestInterface::class)
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
     * @param list<class-string<AbstractMiddleware>> $guardsNames
     *
     * @return list<AbstractMiddleware>
     */
    private function getGuards(array $guardsNames): array
    {
        return array_map(
            function (string $guardClassName): AbstractMiddleware {
                /** @var AbstractMiddleware */
                return $this->container
                    ->get($guardClassName);
            },
            $guardsNames
        );
    }
}
