<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UriInterface;

interface RedirectResponseFactoryInterface extends ResponseFactoryInterface
{
    public function setUri(UriInterface | string $uri): self;

    public function createResponse(
        int $code = StatusCodeInterface::STATUS_FOUND,
        string $reasonPhrase = ''
    ): RedirectResponse;
}
