<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseFactoryInterface;

interface HtmlResponseFactoryInterface extends ResponseFactoryInterface
{
    public function createResponse(
        int $code = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ): HtmlResponse;
}
