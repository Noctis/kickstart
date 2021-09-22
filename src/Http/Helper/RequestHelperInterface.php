<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Psr\Http\Message\ServerRequestInterface;

interface RequestHelperInterface
{
    public function get(ServerRequestInterface $request, string $key, mixed $default = null): mixed;
}
