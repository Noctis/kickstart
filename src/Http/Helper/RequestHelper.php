<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Psr\Http\Message\ServerRequestInterface;

final class RequestHelper implements RequestHelperInterface
{
    public function get(ServerRequestInterface $request, string $key, mixed $default = null): mixed
    {
        $value = $request->getAttribute($key);

        if ($value !== null) {
            return $value;
        }

        $parsedBody = $request->getParsedBody();

        if (is_array($parsedBody) && array_key_exists($key, $parsedBody)) {
            return $parsedBody[$key];
        }

        $queryParams = $request->getQueryParams();

        return $queryParams[$key] ?? $default;
    }
}
