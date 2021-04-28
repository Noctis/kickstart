<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\RouteInfo;

use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerDefinitionInterface;

interface RouteInfoInterface
{
    public function getRouteHandlerDefinition(): RouteHandlerDefinitionInterface;

    /**
     * @return array<string, string>
     */
    public function getRequestVars(): array;
}
