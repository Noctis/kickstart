<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Http\Routing\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class WebApplication implements ApplicationInterface
{
    private RouterInterface $router;
    private RequestHandlerInterface $requestHandler;
    private EmitterInterface $responseEmitter;
    private ServerRequestInterface $request;

    public function __construct(
        RouterInterface $router,
        RequestHandlerInterface $requestHandler,
        EmitterInterface $responseEmitter,
        ServerRequestInterface $request
    ) {
        $this->router = $router;
        $this->requestHandler = $requestHandler;
        $this->responseEmitter = $responseEmitter;
        $this->request = $request;
    }

    public function run(): void
    {
        $this->requestHandler
            ->setRouter($this->router);

        $response = $this->requestHandler
            ->handle($this->request);

        $this->responseEmitter
            ->emit($response);
    }

    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->router
            ->setRoutes($routes);
    }
}
