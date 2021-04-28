<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;

final class NotFoundHandler implements NotFoundHandlerInterface
{
    public function handle(array $routeInfo): ResponseInterface
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
