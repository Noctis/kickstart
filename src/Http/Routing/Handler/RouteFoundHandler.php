<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use Noctis\KickStart\Http\Middleware\RequestHandlerStack;
use Noctis\KickStart\Http\Routing\RouteInfo\FoundRouteInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RouteFoundHandler implements RouteHandlerInterface
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(array $routeInfo): Response
    {
        $routeInfo = FoundRouteInfo::createFromArray($routeInfo);
        $this->container
            ->set(
                'request.vars',
                $routeInfo->getRequestVars()
            );

        $stack = new RequestHandlerStack(
            $this->container,
            $routeInfo->getActionClassName(),
            $routeInfo->getGuardNames()
        );

        return $stack->handle(
            $this->container
                ->get(Request::class)
        );
    }
}