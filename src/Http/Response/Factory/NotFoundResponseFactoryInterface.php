<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseFactoryInterface;

interface NotFoundResponseFactoryInterface extends ResponseFactoryInterface
{
    public function createResponse(
        int $code = StatusCodeInterface::STATUS_NOT_FOUND,
        string $reasonPhrase = ''
    ): EmptyResponse | TextResponse;

    //public function notFound(string $message = null): EmptyResponse | TextResponse;
}
