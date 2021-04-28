<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\RouteInfo;

use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerDefinition;
use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerDefinitionInterface;

final class FoundRouteInfo implements RouteInfoInterface
{
    private RouteHandlerDefinitionInterface $routeHandlerDefinition;

    /** @var array<string, string> */
    private array $requestVars;

    public static function createFromArray(array $routeInfo): self
    {
        /** @var array{0: string, 1: array} $handlerInfo */
        $handlerInfo = $routeInfo[1];
        $routeHandlerDefinition = RouteHandlerDefinition::createFromValue($handlerInfo);

        /** @var array<string, string> $requestVars */
        $requestVars = $routeInfo[2];

        return new self($routeHandlerDefinition, $requestVars);
    }

    /**
     * @param array<string, string> $requestVars
     */
    public function __construct(RouteHandlerDefinitionInterface $routeHandlerDefinition, array $requestVars)
    {
        $this->routeHandlerDefinition = $routeHandlerDefinition;
        $this->requestVars = $requestVars;
    }

    public function getRouteHandlerDefinition(): RouteHandlerDefinitionInterface
    {
        return $this->routeHandlerDefinition;
    }

    /**
     * @inheritDoc
     */
    public function getRequestVars(): array
    {
        return $this->requestVars;
    }
}
