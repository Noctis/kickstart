<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Request extends ServerRequest implements ServerRequestInterface
{
    public static function createFromServerRequest(ServerRequestInterface $request): self
    {
        return new static(
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
    }
}
