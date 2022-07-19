<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Request extends ServerRequest implements ServerRequestInterface
{
    public static function createFromServerRequest(ServerRequestInterface $request): self
    {
        $newRequest = new static(
            $request->getServerParams(),
            $request->getUploadedFiles(),
            $request->getUri(),
            $request->getMethod(),
            $request->getBody(),
            $request->getHeaders(),
            $request->getCookieParams(),
            $request->getQueryParams(),
            $request->getParsedBody(),
            $request->getProtocolVersion()
        );

        /** @var array<string, mixed> $attributes */
        $attributes = $request->getAttributes();
        foreach ($attributes as $name => $value) {
            $newRequest = $newRequest->withAttribute($name, $value);
        }

        return $newRequest;
    }
}
