<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\Handler;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Noctis\KickStart\Http\Middleware\RequestHandlerStack;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\FoundRouteInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RouteFoundHandler implements RouteFoundHandlerInterface
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

        $routeHandlerDefinition = $routeInfo->getRouteHandlerDefinition();
        $stack = new RequestHandlerStack(
            $this->container,
            $this->getAction(
                $routeHandlerDefinition->getActionClassName()
            ),
            $this->getGuards(
                $routeHandlerDefinition->getGuardNames()
            )
        );

        return $stack->handle(
            $this->container
                ->get(Request::class)
        );
    }

    /**
     * @param class-string<AbstractAction> $actionClassName
     */
    private function getAction(string $actionClassName): AbstractAction
    {
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
            fn (string $guardClassName) => $this->container->get($guardClassName),
            $guardsNames
        );
    }
}