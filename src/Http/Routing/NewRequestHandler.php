<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use FastRoute\Dispatcher;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Noctis\KickStart\Http\Routing\Handler\ActionInvokerInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\FoundRouteInfo;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfoInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

use function FastRoute\simpleDispatcher;

final class NewRequestHandler implements RequestHandlerInterface
{
    private ContainerInterface $container;
    private HttpInfoProviderInterface $httpInfoProvider;
    private RoutesParserInterface $routesParser;
    private RoutesLoaderInterface $routesLoader;
    private ActionInvokerInterface $actionInvoker;

    /** @var list<array> */
    private array $routes = [];

    public function __construct(
        ContainerInterface $container,
        HttpInfoProviderInterface $httpInfoProvider,
        RoutesParserInterface $routesParser,
        RoutesLoaderInterface $routesLoader,
        ActionInvokerInterface $actionInvoker
    ) {
        $this->container = $container;
        $this->httpInfoProvider = $httpInfoProvider;
        $this->routesParser = $routesParser;
        $this->routesLoader = $routesLoader;
        $this->actionInvoker = $actionInvoker;
    }

    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $this->httpInfoProvider
            ->getUri();

        $dispatchInfo = $this->getDispatcher()
            ->dispatch(
                $request->getMethod(),
                $uri
            );

        switch ($dispatchInfo[0]) {
            case Dispatcher::FOUND:
                $routeInfo = FoundRouteInfo::createFromArray($dispatchInfo);
                $response = $this->found($request, $routeInfo);
                break;

            case Dispatcher::NOT_FOUND:
                $response = $this->notFound();
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = $this->methodNotAllowed($dispatchInfo[1]);
                break;

            default:
                throw new RuntimeException();
        }

        return $response;
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
