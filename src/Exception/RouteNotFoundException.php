<?php

declare(strict_types=1);

namespace Noctis\KickStart\Exception;

use InvalidArgumentException;

use function Psl\Str\format;

final class RouteNotFoundException extends InvalidArgumentException
{
    public function __construct(string $name)
    {
        parent::__construct(
            format('Could not find route with given name: "%s".', $name)
        );
    }
}
