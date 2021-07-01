<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Laminas\Diactoros\ServerRequestFactory as LaminasServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createFromGlobals(): ServerRequestInterface
    {
        return LaminasServerRequestFactory::fromGlobals();
    }
}
