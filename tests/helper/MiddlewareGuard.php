<?php

declare(strict_types=1);

namespace Tests\Helper;

use Laminas\Diactoros\Response;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareGuard extends AbstractMiddleware
{
    private ?Response $response = null;

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($this->response !== null) {
            return $this->response;
        }

        return parent::process($request, $handler);
    }
}
