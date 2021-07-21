<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\RoutesParser;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\RoutesParser;
use PHPUnit\Framework\TestCase;

final class ParseTests extends TestCase
{
    /**
     * @covers \Noctis\KickStart\Http\Routing\RoutesParser::parse
     */
    public function test_it_creates_route_definition_from_given_route_with_no_guards(): void
    {
        $route = ['GET', '/foo', 'App\Http\Action\FooAction'];
        $parser = new RoutesParser();

        $routes = $parser->parse([$route]);

        $this->assertSame(
            'GET',
            $routes[0]->getMethod()
        );
        $this->assertSame(
            '/foo',
            $routes[0]->getPath(),
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $routes[0]->getAction()
        );
        $this->assertSame(
            [],
            $routes[0]->getGuards()
        );
    }

    /**
     * @covers \Noctis\KickStart\Http\Routing\RoutesParser::parse
     */
    public function test_it_creates_route_definition_from_given_route_with_guards(): void
    {
        $guards = [
            'App\Http\Middleware\FirstGuard',
            'App\Http\Middleware\SecondGuard',
        ];
        $route = ['GET', '/foo', 'App\Http\Action\FooAction', $guards];
        $parser = new RoutesParser();

        $routes = $parser->parse([$route]);

        $this->assertSame(
            'GET',
            $routes[0]->getMethod()
        );
        $this->assertSame(
            '/foo',
            $routes[0]->getPath(),
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $routes[0]->getAction()
        );
        $this->assertSame(
            $guards,
            $routes[0]->getGuards()
        );
    }

    /**
     * @covers \Noctis\KickStart\Http\Routing\RoutesParser::parse
     */
    public function test_it_returns_a_list_of_route_definitions_from_given_list_of_routes(): void
    {
        $routesArray = [
            ['GET', '/foo', 'App\Http\Action\FooAction'],
            ['POST', '/bar', 'App\Http\Action\BarAction']
        ];
        $parser = new RoutesParser();

        $routes = $parser->parse($routesArray);

        $this->assertContainsOnlyInstancesOf(
            RouteInterface::class,
            $routes
        );
        $this->assertCount(
            count($routesArray),
            $routes
        );
    }
}
