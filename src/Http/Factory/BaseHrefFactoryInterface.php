<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use Psr\Http\Message\ServerRequestInterface;

interface BaseHrefFactoryInterface
{
    public function createFromRequest(ServerRequestInterface $request): string;
}
