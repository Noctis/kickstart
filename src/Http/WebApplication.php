<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use DI\Container;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Http\Routing\MiddlewareStack;
use Noctis\KickStart\Http\Routing\MiddlewareStackHandlerInterface;
use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;
use Noctis\KickStart\Http\Service\RequestDecoratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class WebApplication implements ApplicationInterface
{
    private Container $container;
    private ServerRequestInterface $request;
    private RouterInterface $router;
    private MiddlewareStackHandlerInterface $middlewareStackHandler;
    private RequestDecoratorInterface $requestDecorator;
    private EmitterInterface $responseEmitter;

    public function __construct(
        Container $container,
        ServerRequestInterface $request,
        RouterInterface $router,
        MiddlewareStackHandlerInterface $middlewareStackHandler,
        RequestDecoratorInterface $requestDecorator,
        EmitterInterface $responseEmitter
    ) {
        $this->container = $container;
        $this->request = $request;
        $this->router = $router;
        $this->middlewareStackHandler = $middlewareStackHandler;
        $this->requestDecorator = $requestDecorator;
        $this->responseEmitter = $responseEmitter;
    }

    public function run(): void
    {
        $response = $this->generateResponse();

        $this->responseEmitter
            ->emit($response);
    }

    private function generateResponse(): ResponseInterface
    {
        $route = $this->determineRoute();
        $this->prepareRequestHandler($route);
        $this->decorateRequest(
            $route->getAdditionalVars()
        );

        return $this->middlewareStackHandler
            ->handle($this->request);
    }

    private function determineRoute(): RouteInterface
    {
        return $this->router
            ->route($this->request);
    }

    private function prepareRequestHandler(RouteInterface $route): void
    {
        $this->middlewareStackHandler
            ->setMiddlewareStack(
                MiddlewareStack::createFromRoute($route)
            );
    }

    /**
     * @param array<string, string> $additionalVars
     */
    private function decorateRequest(array $additionalVars): void
    {
        $this->request = $this->requestDecorator
            ->withAttributes($this->request, $additionalVars);
        $this->container
            ->set(ServerRequestInterface::class, $this->request);
    }
}
