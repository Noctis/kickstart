<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\ApplicationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class WebApplication implements ApplicationInterface
{
    private RequestHandlerInterface $requestHandler;
    private EmitterInterface $responseEmitter;
    private ServerRequestInterface $request;

    public function __construct(
        RequestHandlerInterface $requestHandler,
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
}
