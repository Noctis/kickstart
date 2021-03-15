<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

interface HttpRoutesProviderInterface
{
    public function get(): callable;
}
