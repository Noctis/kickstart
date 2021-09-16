<?php

declare(strict_types=1);

namespace Tests\Helper;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareGuard implements MiddlewareInterface
{
    private ?ResponseInterface $response = null;

    public function setResponse(ResponseInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->response !== null) {
            return $this->response;
        }

        return $handler->handle($request);
    }
}
