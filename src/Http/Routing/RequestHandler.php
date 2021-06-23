<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Noctis\KickStart\Http\Routing\Handler\ActionInvokerInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfo;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfoInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

final class RequestHandler implements RequestHandlerInterface
{
    private ContainerInterface $container;
    private RouterInterface $router;
    private ActionInvokerInterface $actionInvoker;

    public function __construct(
        ContainerInterface $container,
        RouterInterface $router,
        ActionInvokerInterface $actionInvoker
    ) {
        $this->container = $container;
        $this->router = $router;
        $this->actionInvoker = $actionInvoker;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dispatchInfo = $this->router
            ->getDispatchInfo($request);

        switch ($dispatchInfo[0]) {
            case Dispatcher::FOUND:
                /** @var array{0: int, 1: array, 2: array} $dispatchInfo */
                $routeInfo = RouteInfo::createFromArray($dispatchInfo);
                $response = $this->found($request, $routeInfo);
                break;

            case Dispatcher::NOT_FOUND:
                $response = $this->notFound();
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                /** @var list<string> $allowedMethods */
                $allowedMethods = $dispatchInfo[1];
                $response = $this->methodNotAllowed($allowedMethods);
                break;

            default:
                throw new RuntimeException();
        }

        return $response;
    }

    private function found(ServerRequestInterface $request, RouteInfoInterface $routeInfo): ResponseInterface
    {
        $vars = $routeInfo->getRequestVars();
        foreach ($vars as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        $routeHandlerInfo = $routeInfo->getRouteHandlerInfo();
        $action = $this->getAction(
            $routeHandlerInfo->getActionClassName()
        );
        $guards = $this->getGuards(
            $routeHandlerInfo->getGuardNames()
        );

        $this->actionInvoker
            ->setAction($action)
            ->setGuards($guards);

        return $this->actionInvoker
            ->handle($request);
    }

    private function notFound(): ResponseInterface
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }

    /**
     * @param list<string> $allowedMethods
     */
    private function methodNotAllowed(array $allowedMethods): ResponseInterface
    {
        return new TextResponse(
            sprintf(
                'Allowed methods: %s.',
                implode(', ', $allowedMethods)
            ),
            StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED
        );
    }

    /**
     * @param class-string<ActionInterface> $actionClassName
     */
    private function getAction(string $actionClassName): ActionInterface
    {
        /** @var ActionInterface */
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
