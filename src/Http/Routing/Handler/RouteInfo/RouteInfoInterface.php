<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\RouteInfo;

use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfoInterface;

interface RouteInfoInterface
{
    public function getRouteHandlerInfo(): RouteHandlerInfoInterface;

    /**
     * @return array<string, string>
     */
    public function getRequestVars(): array;
}
