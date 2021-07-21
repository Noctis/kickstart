<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\Handler\RouteInfo\RouteInfo;

use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfoInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfo;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class RouteInfoTests extends TestCase
{
    use ProphecyTrait;

    /**
     * @covers \Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfo::getRouteHandlerInfo
     * @covers \Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfo::getRequestVars
     */
    public function test_it_returns_correct_information(): void
    {
        $routeHandlerInfo = $this->getRouteHandlerInfo();
        $requestVars = ['foo' => 'bar', 'moo' => 'baz'];

        $routeInfo = new RouteInfo($routeHandlerInfo, $requestVars);

        $this->assertSame(
            $routeHandlerInfo,
            $routeInfo->getRouteHandlerInfo()
        );
        $this->assertSame(
            $requestVars,
            $routeInfo->getRequestVars()
        );
    }

    private function getRouteHandlerInfo(): RouteHandlerInfoInterface
    {
        /** @var RouteHandlerInfoInterface $routeHandlerInfo */
        $routeHandlerInfo = $this->prophesize(RouteHandlerInfoInterface::class);

        /** @noinspection PhpUndefinedMethodInspection */
        return $routeHandlerInfo->reveal();
    }
}
