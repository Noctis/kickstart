<?php

declare(strict_types=1);

namespace Tests\Helper;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TextResponseMiddleware implements MiddlewareInterface
{
    public const DEFAULT_RESPONSE = 'middleware!';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new TextResponse(self::DEFAULT_RESPONSE);
    }
}
