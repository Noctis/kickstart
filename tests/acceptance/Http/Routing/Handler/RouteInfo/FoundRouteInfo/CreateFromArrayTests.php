<?php declare(strict_types=1);
namespace Tests\Acceptance\Http\Routing\Handler\RouteInfo\FoundRouteInfo;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfoInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteInfo\FoundRouteInfo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Noctis\KickStart\Http\Routing\Handler\RouteInfo\FoundRouteInfosds
 */
final class CreateFromArrayTests extends TestCase
{
    public function test_it_is_created_with_given_request_variables(): void
    {
        $requestVars = [
            'foo' => 'bar',
        ];

        $routeInfo = FoundRouteInfo::createFromArray([
            1 => AbstractAction::class,
            2 => $requestVars,
        ]);

        $this->assertInstanceOf(
            RouteHandlerInfoInterface::class,
            $routeInfo->getRouteHandlerInfo()
        );
        $this->assertSame(
            $requestVars,
            $routeInfo->getRequestVars()
        );
    }

    public function test_it_is_created_with_no_request_variables_given(): void
    {
        $routeInfo = FoundRouteInfo::createFromArray([
            1 => AbstractAction::class,
            2 => [],
        ]);

        $this->assertInstanceOf(
            RouteHandlerInfoInterface::class,
            $routeInfo->getRouteHandlerInfo()
        );
        $this->assertSame(
            [],
            $routeInfo->getRequestVars()
        );
    }
}