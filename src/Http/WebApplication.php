<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use DI\Container;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Http\Routing\MiddlewareStack;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class WebApplication implements ApplicationInterface
{
    private Container $container;
    private ServerRequestInterface $request;
    private RouterInterface $router;
    private RequestHandlerInterface $requestHandler;
    private EmitterInterface $responseEmitter;

    public function __construct(
        Container $container,
        ServerRequestInterface $request,
        RouterInterface $router,
        RequestHandlerInterface $requestHandler,
        EmitterInterface $responseEmitter
    ) {
        $this->container = $container;
        $this->request = $request;
        $this->router = $router;
        $this->requestHandler = $requestHandler;
        $this->responseEmitter = $responseEmitter;
    }

    public function run(): void
    {
        $route = $this->router
            ->route($this->request);

        $this->enhanceRequest(
            $route->getAdditionalVars()
        );
        $this->requestHandler
            ->setMiddlewareStack(
                MiddlewareStack::createFromRoute($route)
            );
        $response = $this->requestHandler
            ->handle($this->request);

        $this->responseEmitter
            ->emit($response);
    }

    /**
     * @param array<string, string> $additionalVars
     */
    private function enhanceRequest(array $additionalVars): void
    {
        /**
         * @todo Move elsewhere. Idea: middlewares are known for modifying the request object, but how do I pass $additionalVars into said middleware?
         */
        foreach ($additionalVars as $name => $value) {
            $this->request = $this->request
                ->withAttribute($name, $value);
        }
        $this->container
            ->set(ServerRequestInterface::class, $this->request);
    }
}
