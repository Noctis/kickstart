<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\RoutesCollection;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\RoutesCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class GetNamedRouteTests extends TestCase
{
    use ProphecyTrait;

    public function test_it_returns_route_if_a_route_with_given_name_is_defined(): void
    {
        $collection = new RoutesCollection([
            'foo' => $this->getRoute('/get/foo'),
            'bar' => $this->getRoute('/get/bar'),
            $this->getRoute('/get/buz/zed'),
        ]);

        $route = $collection->getNamedRoute('bar');

        $this->assertSame(
            '/get/bar',
            $route->getPath()
        );
    }

    public function test_it_returns_null_if_a_route_with_given_name_does_not_exits(): void
    {
        $collection = new RoutesCollection([
            'foo' => $this->getRoute('/get/foo'),
            'bar' => $this->getRoute('/get/bar'),
            $this->getRoute('/get/buz/zed'),
        ]);

        $route = $collection->getNamedRoute('baz');

        $this->assertNull($route);
    }

    private function getRoute(string $path): RouteInterface
    {
        /** @var RouteInterface $route */
        $route = $this->prophesize(RouteInterface::class);

        $route->getPath()
            ->willReturn($path);

        return $route->reveal();
    }
}
