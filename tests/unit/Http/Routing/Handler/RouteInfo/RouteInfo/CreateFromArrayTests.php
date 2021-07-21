<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\Handler\RouteInfo\RouteInfo;

use FastRoute\Dispatcher;
use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfoInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfo;
use PHPUnit\Framework\TestCase;

final class CreateFromArrayTests extends TestCase
{
    /**
     * @covers \Noctis\KickStart\Http\Routing\Handler\RouteInfo\RouteInfo::createFromArray
     */
    public function test_it_is_created_properly(): void
    {
        $requestVars = ['foo' => 'bar', 'moo' => 'baz'];
        $handlerInfo = [
            Dispatcher::FOUND,
            ['App\Http\Action\FooAction', []],
            $requestVars
        ];

        $routeInfo = RouteInfo::createFromArray($handlerInfo);

        $this->assertInstanceOf(RouteInfo::class, $routeInfo);
        $this->assertInstanceOf(
            RouteHandlerInfoInterface::class,
            $routeInfo->getRouteHandlerInfo()
        );
        $this->assertSame(
            $requestVars,
            $routeInfo->getRequestVars()
        );
    }
}
