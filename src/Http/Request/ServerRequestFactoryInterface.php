<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactoryInterface
{
    public function createFromGlobals(): ServerRequestInterface;
}
