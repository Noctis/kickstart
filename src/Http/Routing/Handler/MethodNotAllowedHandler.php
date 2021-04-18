<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;

final class MethodNotAllowedHandler implements MethodNotAllowedHandlerInterface
{
    public function handle(array $routeInfo): ResponseInterface
    {
        /** @var list<string> $allowedMethods */
        $allowedMethods = $routeInfo[1];

        return new TextResponse(
            sprintf(
                'Allowed methods: %s.',
                implode(', ', $allowedMethods)
            ),
            StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED
        );
    }
}
