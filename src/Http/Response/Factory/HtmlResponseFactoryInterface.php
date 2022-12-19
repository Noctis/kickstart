<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseFactoryInterface;

interface HtmlResponseFactoryInterface extends ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): HtmlResponse;
}
