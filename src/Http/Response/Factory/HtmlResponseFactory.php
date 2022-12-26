<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;

final class HtmlResponseFactory implements HtmlResponseFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createResponse(
        int $code = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ): HtmlResponse {
        return (new HtmlResponse(''))
            ->withStatus($code, $reasonPhrase);
    }
}
