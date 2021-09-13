<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;

final class NotFoundResponseFactory implements NotFoundResponseFactoryInterface
{
    public function notFound(string $message = null): EmptyResponse | TextResponse
    {
        if ($message !== null && $message !== '') {
            return new TextResponse($message, StatusCodeInterface::STATUS_NOT_FOUND);
        }

        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
