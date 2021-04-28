<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

final class HttpInfoProvider implements HttpInfoProviderInterface
{
    public function getMethod(): ?string
    {
        /** @var string|null */
        return filter_input(
            INPUT_SERVER,
            'REQUEST_METHOD',
            FILTER_SANITIZE_STRING,
            FILTER_NULL_ON_FAILURE
        );
    }

    public function getUri(): ?string
    {
        $uri = $this->getRawUri();
        if ($uri === null) {
            return null;
        }

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return rawurldecode($uri);
    }

    public function getRawUri(): ?string
    {
        /** @var string|null */
        return filter_input(
            INPUT_SERVER,
            'REQUEST_URI',
            FILTER_SANITIZE_URL,
            FILTER_NULL_ON_FAILURE
        );
    }
}
