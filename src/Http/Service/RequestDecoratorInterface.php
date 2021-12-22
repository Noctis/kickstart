<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Psr\Http\Message\ServerRequestInterface;

interface RequestDecoratorInterface
{
    /**
     * @param array<string, string> $attributes
     */
    public function withAttributes(ServerRequestInterface $request, array $attributes): ServerRequestInterface;
}
