<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\RouteInfo;

use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfo;
use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfoInterface;

final class RouteInfo implements RouteInfoInterface
{
    private RouteHandlerInfoInterface $routeHandlerDefinition;

    /** @var array<string, string> */
    private array $requestVars;

    public static function createFromArray(array $routeInfo): self
    {
        /** @var array{0: string, 1: array} $handlerInfo */
        $handlerInfo = $routeInfo[1];
        $routeHandlerInfo = RouteHandlerInfo::createFromValue($handlerInfo);

        /** @var array<string, string> $requestVars */
        $requestVars = $routeInfo[2];

        return new self($routeHandlerInfo, $requestVars);
    }

    /**
     * @param array<string, string> $requestVars
     */
    public function __construct(RouteHandlerInfoInterface $routeHandlerDefinition, array $requestVars)
    {
        $this->routeHandlerDefinition = $routeHandlerDefinition;
        $this->requestVars = $requestVars;
    }

    public function getRouteHandlerInfo(): RouteHandlerInfoInterface
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
