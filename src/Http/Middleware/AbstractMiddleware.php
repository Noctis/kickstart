<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Middleware;

use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @psalm-suppress DeprecatedClass
     */
    protected ResponseFactoryInterface $responseFactory;

    /**
     * @psalm-suppress DeprecatedClass
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
