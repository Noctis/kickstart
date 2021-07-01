<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Http\Routing\NewRequestHandler;
use Psr\Http\Message\ServerRequestInterface;

final class WebApplication implements ApplicationInterface
{
    private NewRequestHandler $requestHandler;
    private EmitterInterface $responseEmitter;
    private ServerRequestInterface $request;

    public function __construct(
        NewRequestHandler $requestHandler,
        EmitterInterface $responseEmitter,
        ServerRequestInterface $request
    ) {
        $this->requestHandler = $requestHandler;
        $this->responseEmitter = $responseEmitter;
        $this->request = $request;
    }

    public function run(): void
    {
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
        $this->requestHandler
            ->setRoutes($routes);
    }
}
