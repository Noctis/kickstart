<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\HtmlResponse;

final class HtmlResponseFactory implements HtmlResponseFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): HtmlResponse
    {
        return (new HtmlResponse(''))
            ->withStatus($code, $reasonPhrase);
    }
}
