<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Psr\Http\Message\ServerRequestInterface;

final class RequestDecorator implements RequestDecoratorInterface
{
    /**
     * @inheritDoc
     */
    public function withAttributes(ServerRequestInterface $request, array $attributes): ServerRequestInterface
    {
        foreach ($attributes as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $request;
    }
}
