<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\UriInterface;

final class RedirectResponseFactory implements RedirectResponseFactoryInterface
{
    private UriInterface | string | null $uri;

    public function __construct()
    {
        $this->uri = null;
    }

    public function setUri(UriInterface | string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function createResponse(
        int $code = StatusCodeInterface::STATUS_FOUND,
        string $reasonPhrase = ''
    ): RedirectResponse {
        /** @psalm-suppress PossiblyNullArgument `RedirectResponse`'s constructor will take care of this */
        return (new RedirectResponse($this->uri))
            ->withStatus($code, $reasonPhrase);
    }
}
