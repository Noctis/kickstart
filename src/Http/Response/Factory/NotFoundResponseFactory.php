<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;

use function Psl\Str\is_empty;

final class NotFoundResponseFactory implements NotFoundResponseFactoryInterface
{
    public function createResponse(
        int $code = StatusCodeInterface::STATUS_NOT_FOUND,
        string $reasonPhrase = ''
    ): EmptyResponse | TextResponse {
        $response = $reasonPhrase === ''
            ? new EmptyResponse()
            : new TextResponse($reasonPhrase);

        return $response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND, $reasonPhrase);
    }
}
